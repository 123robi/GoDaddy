<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TeamMembers Controller
 *
 * @property \App\Model\Table\TeamMembersTable $TeamMembers
 *
 * @method \App\Model\Entity\TeamMember[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TeamMembersController extends AppController
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

		$this->set('team', $this->Teams->get($this->request->getParam('team_id')));
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
		$teamMember = $this->TeamMembers->newEntity();
		if ($this->request->is('post')) {
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
		$this->request->allowMethod(['post', 'delete']);
		$teamMember = $this->TeamMembers->get($id);
		if ($this->TeamMembers->delete($teamMember)) {
			$this->Flash->success(__('The team member has been deleted.'));
		} else {
			$this->Flash->error(__('The team member could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
