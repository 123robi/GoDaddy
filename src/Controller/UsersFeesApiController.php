<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\I18n\Time;


class UsersFeesApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Fees');
		$this->loadModel('Teams');
		$this->loadModel('UsersFees');
		$this->loadModel('Users');
	}
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow();
	}

	public function addFee()
	{
		$email = $this->request->getData('email');
		$connection_number = $this->request->getData('connection_number');
		$id = $this->request->getData('id');

		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();
		if (empty($team) || is_null($team)) {
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => "Team with such an ID does not exists!"
			]);
			return $this->response->withType("json")->withStringBody($resultJ);
		}
		$fee = $this->Fees
			->find('all')
			->where(['team_id' => $team->id])
			->where(['id' => $id])
			->first();
		if (empty($user)) {
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => "User with such an ID does not exists!"
			]);
			return $this->response->withType("json")->withStringBody($resultJ);
		}  elseif (empty($fee)) {
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => "Fee does not exists!"
			]);
			return $this->response->withType("json")->withStringBody($resultJ);
		} else {
			$add_fee = [
				'user_id' => $user->id,
				'fee_id' => $fee->id,
				'team_id' => $team->id,
				'paid' => 0,
				'date' => Time::now()
			];

		}
		$users_fee = $this->UsersFees->newEntity($add_fee);

		if ($this->request->is('post')) {
			try {
				if ($this->UsersFees->save($users_fee)) {
					$resultJ = json_encode([
						'error' => false
					]);
				} else {
					$resultJ = json_encode([
						'error' => true,
						'error_msg' => "Problem when saving a user fee has occured!"
					]);
				}
			} catch (\Exception $e) {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Unknown problem"
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);

	}
}
