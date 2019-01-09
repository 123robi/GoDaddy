<?php

namespace App\Controller;


use Cake\Event\Event;

class PlacesApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Places');
		$this->loadModel('Teams');
	}
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow();
	}
	public function isAuthorized($user)
	{
		return true;
	}
	public function add() {
		$name = $this->request->getData('name');
		$address = $this->request->getData('address');
		$latlng = $this->request->getData('latlng');
		$connection_number = $this->request->getData('connection_number');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();
		$place_data = [
			'name' => $name,
			'address' => $address,
			'latlng' => $latlng,
			'team_id' => $team->id
		];
		$place = $this->Places->newEntity($place_data);

		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($address) && !is_null($latlng) && !is_null($connection_number)) {
				if ($this->Places->save($place)) {
					$resultJ = json_encode([
						'error' => false,
						'fee' => $place,
					]);
				} else {
					$resultJ = json_encode([
						'error' => true,
						'error_msg' => "Place already exists"
					]);
				}
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters (name, cost, connection_number) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function getPlaces() {

		$connection_number = $this->request->getQuery('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$places = $this->Places
			->find('all')
			->where(['team_id' => $team->id]);

		return $this->response->withType("json")->withStringBody(json_encode(['places' => $places]));
	}
	public function getPlaceById() {
		$id = $this->request->getQuery('id');

		$place = $this->Places
			->find('all')
			->where(['id' => $id])->first();

		return $this->response->withType("json")->withStringBody(json_encode($place));
	}

}
