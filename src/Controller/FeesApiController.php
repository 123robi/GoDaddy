<?php

namespace App\Controller;


use Cake\Event\Event;

class FeesApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Fees');
		$this->loadModel('Teams');
		$this->loadModel('Users');
		$this->loadModel('UsersFees');
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
		$cost = $this->request->getData('cost');
		$connection_number = $this->request->getData('connection_number');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();
		$fee_data = [
			'name' => $name,
			'cost' => $cost,
			'team_id' => $team->id
		];
		$fee = $this->Fees->newEntity($fee_data);

		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($cost) && !is_null($connection_number)) {
				if ($this->Fees->save($fee)) {
					$resultJ = json_encode([
						'error' => false,
						'fee' => $fee,
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
					'error_msg' => "Required parameters (name, cost, connection_number) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function getFeeOfUserByEmail() {
		$email = $this->request->getQuery('email');
		$connection_number = $this->request->getQuery('connection_number');

		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$fees = $this->UsersFees->find();
		$fees->innerJoinWith('Fees');
		$fees
			->select(['UsersFees.id','UsersFees.paid','UsersFees.date','Fees.name','Fees.cost'])
			->where([ 'UsersFees.user_id' => $user->id, 'UsersFees.team_id' => $team->id])
			->order(['UsersFees.paid', 'UsersFees.date']);

		return $this->response->withType("json")->withStringBody(json_encode(['fees' => $fees]));
	}
	public function updateFeeOfUserByEmail() {
		$id = $this->request->getData('id');

		$entity = $this->UsersFees->get($id);

		$entity->paid = 1;

		$this->UsersFees->save($entity);

		$resultJ = json_encode([
			'error' => false,
		]);


		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function getTop3FinedUsers() {
		$connection_number = $this->request->getQuery('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$top3 = $this->UsersFees->find();
		$top3->innerJoinWith('Users');
		$top3->innerJoinWith('Fees');
		$top3
			->select(['Users.id','Users.name','Users.email','Users.phone_number','Users.address','sum' => $top3->func()->sum('Fees.cost')])
			->where(['UsersFees.team_id' => $team->id, 'UsersFees.paid' => 0])
			->group(['UsersFees.user_id'])
			->order(['sum' => 'DESC'])
			->limit(7)
		;

		return $this->response->withType("json")->withStringBody(json_encode(['members' => $top3]));

	}
}
