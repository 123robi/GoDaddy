<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Places Controller
 *
 * @property \App\Model\Table\PlacesTable $Places
 *
 * @method \App\Model\Entity\Place[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PlacesController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('TeamMembers');
		$this->loadModel('Events');
		$this->loadModel('Places');
		$this->loadModel('Teams');
	}

	public function isAuthorized($user)
	{
		if (in_array($this->request->getParam('action'), ['index','add','view'])) {
			return true;
		}

		if (in_array($this->request->getParam('action'), ['delete'])) {
			$teamId = (int)$this->request->getParam('pass.0');
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
		$places = $this->paginate($this->Places);

		$this->set(compact('places'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Place id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$place = $this->Places->get($id, [
			'contain' => ['Teams', 'Events']
		]);

		$this->set('place', $place);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$place = $this->Places->newEntity();
		if ($this->request->is('post')) {
			$place = $this->Places->patchEntity($place, $this->request->getData());
			$place->team_id = $this->request->getParam('team_id');
			if ($this->Places->save($place)) {
				$this->Flash->success(__('The place has been saved.'));

				return $this->redirect(['controller' => 'events', 'action' => 'add', 'team_id' => $this->request->getParam('team_id')]);
			}
			$this->Flash->error(__('The place could not be saved. Please, try again.'));
		}

		$this->set('place', $place);
		$this->set('team', $this->Teams->get($this->request->getParam('team_id')));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Place id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$place = $this->Places->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$place = $this->Places->patchEntity($place, $this->request->getData());
			if ($this->Places->save($place)) {
				$this->Flash->success(__('The place has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The place could not be saved. Please, try again.'));
		}
		$teams = $this->Places->Teams->find('list', ['limit' => 200]);
		$this->set(compact('place', 'teams'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Place id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$place = $this->Places->get($id);
		if ($this->Places->delete($place)) {
			$this->Flash->success(__('The place has been deleted.'));
		} else {
			$this->Flash->error(__('The place could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
