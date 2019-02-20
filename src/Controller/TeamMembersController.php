<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * TeamMembers Controller
 *
 * @property \App\Model\Table\TeamMembersTable $TeamMembers
 *
 * @method \App\Model\Entity\TeamMember[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TeamMembersController extends AppController
{

    const URL = "https://api.paylibo.com/paylibo/generator/czech/image?accountNumber=";

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('UsersFees');
		$this->loadModel('Events');
		$this->loadModel('Places');
        $this->loadModel('Teams');
        $this->loadModel('Payments');
	}

	public function beforeFilter(Event $event)
	{
		$teamId = $this->request->getParam('team_id');
		$userID = $this->Auth->user('id');
		if ($this->TeamMembers->isAdmin($teamId, $userID)) {
			$this->set('is_admin', true);
		} else {
			$this->set('is_admin', false);
		}
	}

	public function isAuthorized($user)
	{
		if (in_array($this->request->getParam('action'), ['index','view'])) {
			return true;
		}

		if (in_array($this->request->getParam('action'), ['delete','add'])) {
			$teamId = $this->request->getParam('team_id');
			if ($this->TeamMembers->isAdmin($teamId, $user['id'])) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index()
	{
		$team = $this->Teams->get($this->request->getParam('team_id'));

		$admin = $this->Users->find();
		$admin->innerJoinWith('TeamMembers', function ($q) {
			$team = $this->Teams->get($this->request->getParam('team_id'));
			return $q->where(['TeamMembers.team_id' => $team->id,'TeamMembers.is_admin' => 1]);
		});

		$members = $this->Users->find();
		$members->innerJoinWith('TeamMembers', function ($q) {
			$team = $this->Teams->get($this->request->getParam('team_id'));
			return $q->where(['TeamMembers.team_id' => $team->id, 'TeamMembers.is_admin' => 0]);
		});

		$members->select(['id', 'name','email','phone_number','address']);

		$fees = $this->UsersFees->find();
		$fees->innerJoinWith('Fees');
		$fees
			->select(['UsersFees.user_id','sum' => $fees->func()->sum('Fees.cost')])
			->where(['UsersFees.paid' => 0])
			->andWhere(['UsersFees.team_id' => $team->id])
			->group('UsersFees.user_id');

		$this->set('members', $members);
		$this->set('admin', $admin);
		$this->set('fees', $fees);
		$this->set('team', $team);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Team Member id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$member = $this->Users->get($id);

		$fees = $this->UsersFees->find();
		$fees->innerJoinWith('Fees');
		$fees
			->select(['UsersFees.id','UsersFees.paid','UsersFees.date','Fees.name','Fees.cost'])
			->where([ 'UsersFees.user_id' => $member->id])
			->order(['UsersFees.paid', 'UsersFees.date']);

        $team = $this->Teams->get($this->request->getParam('team_id'));
        $feesQR = $this->UsersFees->find('all')->where(['user_id' => $id, 'team_id' => $team->id, 'paid' => 0]);

        $sum = 0;
        foreach ($feesQR as $fee) {
            $sum += $fee->cost;
        }

        $payment = $this->Payments->find('all')->where(['team_id' => $team->id])->first();
        if ($this->Auth->user('id') == $id && !empty($payment) && $team->currency_code == "CZK") {
            $this->set('show', true);
            $url = self::URL . $payment->account_number .
                "&bankCode=" . $payment->bank_code .
                "&amount=" . sprintf('%0.2f', $sum) .
                "&currency=" . $team->currency_code .
                "&message=" . $member->name;
            $this->set('url', $url);
        } else {
            $this->set('show', false);
        }

		$this->set('team', $team);
		$this->set('member', $member);
		$this->set('fees', $fees);
	}
	/**
	 * View method
	 *
	 * @param string|null $id Team Member id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function change($fee_id) {
		dump( $this->request);

//		$fee = $this->UsersFees->get($fee_id);
//		$fee->paid = 1;
//		$this->UsersFees->save($fee);
//
//		return $this->redirect(['action' => 'view', 'team_id' => $this->request->getParam('team_id')]);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$team = $this->Teams->get($this->request->getParam('team_id'));

		$user = $this->Users->newEntity();
		$teamMember = $this->TeamMembers->newEntity();
		if ($this->request->is('post')) {
//			$teamMember = $this->TeamMembers->patchEntity($teamMember, $this->request->getData());
			$user = $this->Users
				->find('all')
				->where(['email' => $this->request->getData()['email']])->first();

			if(empty($user)) {
				$hash = $this->hashSSHA('dcf0c766059e870b45db92278593519b');
				$user_data = [
					'name' => $this->request->getData()['name'],
					'email' => $this->request->getData()['email'],
					'facebook_json' => '',
					'password' => $hash['encrypted'],
					'salt' => $hash['salt'],
					'real_user' => 0,
				];
				$user = $this->Users->newEntity($user_data);
			} else {
				$this->Flash->error(__('User with this email already exists'));
			}
			if ($this->Users->save($user)) {
				$this->TeamMembers->save($this->TeamMembers->newEntity([
					'team_id' => $team->id,
					'user_id' => $user->id,
					'is_admin' => $this->request->getData()['admin']
				]));
				$this->Flash->success(__('The team member has been saved.'));

				return $this->redirect(['action' => 'index','team_id' => $team->id]);
			}
			$this->Flash->error(__('The team member could not be saved. Please, try again.'));
		}
		$this->set(compact('user', 'team'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Team Member id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$teamMember = $this->TeamMembers->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$teamMember = $this->TeamMembers->patchEntity($teamMember, $this->request->getData());
			if ($this->TeamMembers->save($teamMember)) {
				$this->Flash->success(__('The team member has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The team member could not be saved. Please, try again.'));
		}
		$users = $this->TeamMembers->Users->find('list', ['limit' => 200]);
		$teams = $this->TeamMembers->Teams->find('list', ['limit' => 200]);
		$this->set(compact('teamMember', 'users', 'teams'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Team Member id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$team = $this->Teams->get($this->request->getParam('team_id'));

		$this->request->allowMethod(['post', 'delete']);
		$teamMember = $this->TeamMembers->find()->where(['user_id' => $id,'team_id' => $team->id])->first();
		if ($this->TeamMembers->delete($teamMember)) {
			$this->Flash->success(__('The team member has been deleted.'));
		} else {
			$this->Flash->error(__('The team member could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index','team_id' => $team->id]);
	}
	public function hashSSHA($password) {

		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}
}
