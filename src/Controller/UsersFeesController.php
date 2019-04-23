<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Event\Event;

/**
 * UsersFees Controller
 *
 * @property \App\Model\Table\UsersFeesTable $UsersFees
 *
 * @method \App\Model\Entity\UsersFee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersFeesController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('UsersFees');
		$this->loadModel('Events');
		$this->loadModel('Places');
		$this->loadModel('Teams');
		$this->loadModel('Fees');
		$this->loadModel('TeamMembers');
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
        if (!empty($this->Auth->user('id'))) {
            $user = $this->Users->get($this->Auth->user('id'));
            if (empty($user->phone_number) && empty($user->address) || strpos($user->email, 'required') !== false) {
                return $this->redirect(['controller' => 'Users' , 'action' => 'setPassword']);
            }
        }

		if (in_array($this->request->getParam('action'), ['index','view'])) {
			return true;
		}

		if (in_array($this->request->getParam('action'), ['delete','add','change'])) {
			$teamId = $this->request->getParam('team_id');
			if ($this->TeamMembers->isAdmin($teamId, $user['id'])) {
				return true;
			}
		}

		return false;
	}
	/**
	 * Change method
	 *
	 * @return \Cake\Http\Response
	 */
	public function change($user_id,$fee_id)
	{
		$member_fee = $this->UsersFees->get($fee_id);
		$member_fee->paid = 1;
		$this->UsersFees->save($member_fee);

		$team = $this->Teams->get($this->request->getParam('team_id'));

		return $this->redirect(['controller' => 'TeamMembers','action'=>'view','team_id' => $team->id,$user_id]);
	}
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index()
	{
		$this->paginate = [
			'contain' => ['Users', 'Fees', 'Teams']
		];
		$usersFees = $this->paginate($this->UsersFees);

		$this->set(compact('usersFees'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Users Fee id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$usersFee = $this->UsersFees->get($id, [
			'contain' => ['Users', 'Fees', 'Teams']
		]);

		$this->set('usersFee', $usersFee);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$team = $this->Teams->get($this->request->getParam('team_id'));
		$usersFee = $this->UsersFees->newEntity();
		if ($this->request->is('post')) {
			$usersFee = $this->UsersFees->patchEntity($usersFee, $this->request->getData());
			$usersFee->team_id = $team->id;
			$usersFee->paid = 0;
			//$usersFee->date = Time::now();
			if ($this->UsersFees->save($usersFee)) {
				$this->Flash->success(__('The users fee has been saved.'));

				return $this->redirect(['action' => 'add','team_id' => $team->id]);
			}
			$this->Flash->error(__('The users fee could not be saved. Please, try again.'));
		}
		$users = $this->Users->find('list');
		$users->innerJoinWith('TeamMembers', function ($q) {
			$team = $this->Teams->get($this->request->getParam('team_id'));
			return $q->where(['TeamMembers.team_id' => $team->id]);
		});
		$fees = $this->Fees->find('list')->where(['team_id' => $team->id]);

		$userFees = $this->UsersFees->find('all')->orderDesc('date')->limit(8);
		$userFees->innerJoinWith('Users');
		$userFees->innerJoinWith('Fees');
		$userFees->select(['Users.id','Users.name','UsersFees.paid','UsersFees.date','Fees.name']);

		$this->set('team', $team);
		$this->set(compact('usersFee', 'users', 'fees','userFees'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Users Fee id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$usersFee = $this->UsersFees->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$usersFee = $this->UsersFees->patchEntity($usersFee, $this->request->getData());
			if ($this->UsersFees->save($usersFee)) {
				$this->Flash->success(__('The users fee has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The users fee could not be saved. Please, try again.'));
		}
		$users = $this->UsersFees->Users->find('list', ['limit' => 200]);
		$fees = $this->UsersFees->Fees->find('list', ['limit' => 200]);
		$teams = $this->UsersFees->Teams->find('list', ['limit' => 200]);
		$this->set(compact('usersFee', 'users', 'fees', 'teams'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Users Fee id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$usersFee = $this->UsersFees->get($id);
		if ($this->UsersFees->delete($usersFee)) {
			$this->Flash->success(__('The users fee has been deleted.'));
		} else {
			$this->Flash->error(__('The users fee could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
