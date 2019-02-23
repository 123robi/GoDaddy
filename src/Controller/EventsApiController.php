<?php

namespace App\Controller;


use Cake\Event\Event;

class EventsApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Events');
		$this->loadModel('Teams');
		$this->loadModel('Places');
	}

	public function isAuthorized($user)
	{
		return true;
	}

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow();
	}

	public function add() {
		$name = $this->request->getData('name');
		$start_date = $this->request->getData('start');
		$end_date = $this->request->getData('end');
		$description  = $this->request->getData('description');
		$connection_number = $this->request->getData('connection_number');
		$place_name = $this->request->getData('place_name');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();
		$place = $this->Places
			->find('all')
			->where(['team_id' => $team->id, 'name' => $place_name])->first();
		$event_data = [
			'name' => $name,
			'start' => $start_date,
			'end' => $end_date,
			'description' => $description,
			'team_id' => $team->id,
			'place_id' => $place->id
		];
		$event = $this->Events->newEntity($event_data);

		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($start_date) && !is_null($connection_number) && !is_null($description)) {
				if ($this->Events->save($event)) {
					$resultJ = json_encode([
						'error' => false,
						'fee' => $event,
					]);
				} else {
					$resultJ = json_encode([
						'error' => true,
						'error_msg' => "Fee Already exists"
					]);
				}
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters (name, date, description, connection_number) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function get() {
		$connection_number = $this->request->getQuery('connection_number');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$events = $this->Events
			->find('all')
			->where(['team_id' => $team->id]);


		return $this->response->withType("json")->withStringBody(json_encode(['events' => $events]));
	}
	public function getEventsMatch() {
        $this->response->header('Access-Control-Allow-Origin', '*');
		$id = $this->request->getQuery('id');

		$team = $this->Teams->get($id);

		$events = $this->Events
			->find('all')
			->where(['team_id' => $team->id,['name' => 'Match']]);


		return $this->response->withType("json")->withStringBody(json_encode($events));
	}
	public function getEventsTraining() {
	    $this->response->header('Access-Control-Allow-Origin', '*');
		$id = $this->request->getQuery('id');

		$team = $this->Teams->get($id);

		$events = $this->Events
			->find('all')
			->where(['team_id' => $team->id,['name' => 'Training']]);


		return $this->response->withType("json")->withStringBody(json_encode($events));
	}
	public function getEventsEvent() {
        $this->response->header('Access-Control-Allow-Origin', '*');
		$id = $this->request->getQuery('id');

		$team = $this->Teams->get($id);

		$events = $this->Events
			->find('all')
			->where(['team_id' => $team->id,['name' => 'Event']]);


		return $this->response->withType("json")->withStringBody(json_encode($events));
	}
	public function getNextEvent() {
		$connection_number = $this->request->getQuery('connection_number');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$events = $this->Events
            ->find();

        $events->innerJoinWith('Places');
        $events
            ->select(['Events.id','Events.name','Events.start','Events.end','Events.description','Events.place_id', 'Places.id','Places.name', 'Places.address','Places.latlng','Places.team_id'])
            ->where(['Events.team_id' => $team->id])
            ->andWhere(['Events.start > NOW()'])
            ->order(['Events.start' => 'ASC'])
            ->limit(1)
        ;

		return $this->response->withType("json")->withStringBody(json_encode(['events' => $events]));
	}

	public function delete() {
		$name = $this->request->getData('name');
		$start_date = $this->request->getData('start');
		$end_date = $this->request->getData('end');
		$description  = $this->request->getData('description');
		$connection_number = $this->request->getData('connection_number');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$event = $this->Events
			->find('all')
			->where([
				'name' => $name,
				'start' => $start_date,
				'end' => $end_date,
				'description' => $description,
				'team_id' => $team->id
				])->first();
		if (empty($event)) {
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => "Event does not exists"
			]);
			return $this->response->withType("json")->withStringBody($resultJ);
		}

		if ($this->Events->delete($event)) {
			$resultJ = json_encode([
				'error' => false,
			]);
		} else {
			$resultJ = json_encode([
				'error' => true,
			]);
		}

		return $this->response->withType("json")->withStringBody($resultJ);
	}


}
