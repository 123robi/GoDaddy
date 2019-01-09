<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Event\Event;

/**
 * Teams Controller
 *
 * @property \App\Model\Table\TeamsTable $Teams
 *
 * @method \App\Model\Entity\Team[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TeamsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('TeamMembers');
		$this->loadModel('Events');
		$this->loadModel('Places');
	}

	public function beforeFilter(Event $event)
	{
		$teamId = $this->request->getParam('pass')[0];
		$userID = $this->Auth->user('id');
		if ($this->TeamMembers->isAdmin($teamId, $userID)) {
			$this->set('is_admin', true);
		} else {
			$this->set('is_admin', false);
		}
	}

	public function isAuthorized($user)
	{
		if (in_array($this->request->getParam('action'), ['index','add','view','delete'])) {
			return true;
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
		$teams = $this->Teams->find();
		$teams->innerJoinWith('TeamMembers');
		$teams
			->find('all')
			->where(['TeamMembers.user_id' => $this->Auth->user('id'),'TeamMembers.is_admin' => 0]);

		$admin = $this->Teams->find();
		$admin->innerJoinWith('TeamMembers');
		$admin
			->find('all')
			->where(['TeamMembers.user_id' => $this->Auth->user('id'),'TeamMembers.is_admin' => 1]);

		$this->set('teams', $teams);
		$this->set('adminTeams', $admin);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Team id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view()
	{

		$id = $this->request->getParam('pass')[0];
//		$is_admin = $this->TeamMembers
//			->find('all')
//			->where(['user_id' => $this->Auth->user('id'), 'team_id' => $id, 'is_admin' => 1])->first();


		$team = $this->Teams->get($id, [
			'contain' => ['Events', 'Fees', 'Places', 'TeamMembers', 'UsersFees']
		]);
		$event = $this->Events
			->find('all')
			->where(['Events.team_id' => $id])
			->andWhere(['Events.start > NOW()'])
			->order(['Events.start' => 'ASC'])
			->first()
		;
		if (!empty($event)) {
			$place = $this->Places->get($event->place_id);
			$this->set('place', $place);
		}
		$this->set('event', $event);
		$this->set('team', $team);

	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{

		$currencies =['CZK'=>'CZK', '€'=>'EUR', '$'=>'USD', 'CHF'=>'CHF'];

		$team = $this->Teams->newEntity();
		if ($this->request->is('post')) {
			$team = $this->Teams->patchEntity($team, $this->request->getData());
			$team->currency_code = $currencies[$team->currency_symbol];
			$team->connection_number = uniqid();
			if ($this->Teams->save($team)) {
				$join_team = [
					'user_id' => $this->Auth->user('id'),
					'team_id' => $team->id,
					'is_admin' => 1
				];
				$team = $this->TeamMembers->newEntity($join_team);
				if ($this->TeamMembers->save($team)) {
					$this->Flash->success(__('The team has been saved.'));
				} else {
					$this->Flash->error(__('The team could not be saved. Please, try again.'));
				}
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The team could not be saved. Please, try again.'));
		}

		$this->set('team', $team);
		$this->set('currencies',$currencies);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Team id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$team = $this->Teams->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$team = $this->Teams->patchEntity($team, $this->request->getData());
			if ($this->Teams->save($team)) {
				$this->Flash->success(__('The team has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The team could not be saved. Please, try again.'));
		}
		$users = $this->Teams->Users->find('list', ['limit' => 200]);
		$this->set(compact('team', 'users'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Team id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$userID = $this->Auth->user('id');
		if ($this->TeamMembers->isAdmin($id, $userID)) {
			$team = $this->Teams->get($id);
			if ($this->Teams->delete($team)) {
				$this->Flash->success(__('The team has been deleted.'));
			} else {
				$this->Flash->error(__('The team could not be deleted. Please, try again.'));
			}
		} else {
			$this->Flash->error(__('You are not authorized to access this location.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
