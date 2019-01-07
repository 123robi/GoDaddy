<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
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
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index()
	{
		$events = $this->Events
			->find('all')
			->where(['team_id' => $this->request->getParam('team_id')]);


		foreach ($events as $row) {
			$eventsInArray[] = [
				'start' => $row['start'],
				'end' => $row['end'],
				'title' => $row['name'],
				'description' => $row['description'],
				'place' => $row['place'],
			];
		}
		$this->set('events', json_encode($events));
		$this->set('team', $this->Teams->get($this->request->getParam('team_id')));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Event id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($event_id)
	{
		$team_id = $this->request->getParam('team_id');
		$is_member = $this->TeamMembers
			->find('all')
			->where(['user_id' => $this->Auth->user('id'), 'team_id' => $team_id])->first();
		if (!empty($is_member)) {
			$team = $this->Teams->get($team_id, [
				'contain' => ['Events', 'Fees', 'Places', 'TeamMembers', 'UsersFees']
			]);
			$event = $this->Events
				->find('all')
				->where(['team_id' => $team_id])
				->andWhere(['id' => $event_id])
				->first()
			;
			if (!empty($event)) {
				$place = $this->Places->get($event->place_id);
				$this->set('place', $place);
			}
			$this->set('event', $event);
			$this->set('team', $team);
		}
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$event = $this->Events->newEntity();
		if ($this->request->is('post')) {
			$event = $this->Events->patchEntity($event, $this->request->getData());
			$event->team_id = $this->request->getParam('team_id');

			if ($this->Events->save($event)) {
				$this->Flash->success(__('The event has been saved.'));
				
				return $this->redirect(['controller' => 'teams', 'action' => 'view', $this->request->getParam('team_id')]);
			}
			$this->Flash->error(__('The event could not be saved. Please, try again.'));
		}
		$places = $this->Events->Places->find('list')->where(['team_id' => $this->request->getParam('team_id')]);

		$this->set(compact('event', 'teams', 'places'));
		$this->set('team', $this->Teams->get($this->request->getParam('team_id')));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Event id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$event = $this->Events->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$event = $this->Events->patchEntity($event, $this->request->getData());
			if ($this->Events->save($event)) {
				$this->Flash->success(__('The event has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The event could not be saved. Please, try again.'));
		}
		$teams = $this->Events->Teams->find('list', ['limit' => 200]);
		$places = $this->Events->Places->find('list', ['limit' => 200]);
		$this->set(compact('event', 'teams', 'places'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Event id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$event = $this->Events->get($id);
		if ($this->Events->delete($event)) {
			$this->Flash->success(__('The event has been deleted.'));
		} else {
			$this->Flash->error(__('The event could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

}
