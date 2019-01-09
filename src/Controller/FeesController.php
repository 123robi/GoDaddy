<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Fees Controller
 *
 * @property \App\Model\Table\FeesTable $Fees
 *
 * @method \App\Model\Entity\Fee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FeesController extends AppController
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
		$this->paginate = [
			'contain' => ['Teams']
		];
		$fees = $this->paginate($this->Fees);

		$this->set(compact('fees'));
	}


	/**
	 * View method
	 *
	 * @param string|null $id Fee id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$fee = $this->Fees->get($id, [
			'contain' => ['Teams', 'Users']
		]);

		$this->set('fee', $fee);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$team = $this->Teams->get($this->request->getParam('team_id'));
		$fee = $this->Fees->newEntity();
		if ($this->request->is('post')) {
			$fee = $this->Fees->patchEntity($fee, $this->request->getData());
			$fee->team_id = $team->id;
			if ($this->Fees->save($fee)) {
				$this->Flash->success(__('The fee has been saved.'));

				return $this->redirect(['action' => 'add','team_id' => $team->id]);
			}
			$this->Flash->error(__('The fee could not be saved. Please, try again.'));
		}

		$fees = $this->Fees->find()->where(['team_id' => $team->id]);
		$this->set(compact('fee', 'team', 'fees'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Fee id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$fee = $this->Fees->get($id, [
			'contain' => ['Users']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$fee = $this->Fees->patchEntity($fee, $this->request->getData());
			if ($this->Fees->save($fee)) {
				$this->Flash->success(__('The fee has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The fee could not be saved. Please, try again.'));
		}
		$teams = $this->Fees->Teams->find('list', ['limit' => 200]);
		$users = $this->Fees->Users->find('list', ['limit' => 200]);
		$this->set(compact('fee', 'teams', 'users'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Fee id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$team = $this->Teams->get($this->request->getParam('team_id'));
		$this->request->allowMethod(['post', 'delete']);
		$fee = $this->Fees->get($id);
		if ($this->Fees->delete($fee)) {
			$this->Flash->success(__('The fee has been deleted.'));
		} else {
			$this->Flash->error(__('The fee could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'add','team_id' => $team->id]);
	}
}
