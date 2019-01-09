<?php

namespace App\Controller;


use Cake\Event\Event;

class TeamsApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('Teams');
		$this->loadModel('Fees');
		$this->loadModel('TeamMembers');
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
		$name = $this->request->getData('team_name');
		$email = $this->request->getData('email');
		$currency_code = $this->request->getData('currency_code');
		$currency_symbol = $this->request->getData('currency_symbol');
		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		$team_data = [
			'team_name' => $name,
			'currency_code' => $currency_code,
			'currency_symbol' => $currency_symbol,
			'connection_number' => uniqid(),
		];
		$team = $this->Teams->newEntity($team_data);

		$team->admin = $user;
		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($email)) {
				if ($this->Teams->save($team)) {
					$join_team = [
						'user_id' => $user->id,
						'team_id' => $team->id,
						'is_admin' => 1
					];

					$team = $this->TeamMembers->newEntity($join_team);
					if ($this->TeamMembers->save($team)) {
						$resultJ = json_encode([
							'error' => false,
							'team' => $team,
						]);
					} else {
						$resultJ = json_encode([
							'error' => true,
							'error_msg' => "Team already exists with this id"
						]);
					}}
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters (name, email or password) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function get() {
		$email = $this->request->getQuery('email');
		$admin = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		$teams = $this->Teams
			->find('all')
			->where(['user_id' => $admin->id]);
		return $this->response->withType("json")->withStringBody(json_encode(['teams' => $teams]));
	}
	public function getUsersInTeam() {
		$query = $this->Users->find();
		$query->innerJoinWith('TeamMembers', function ($q) {
			$connection_number = $this->request->getQuery('connection_number');
			$team = $this->Teams
				->find('all')
				->where(['connection_number' => $connection_number])->first();
			return $q->where(['TeamMembers.team_id' => $team->id]);
		});
		$connection_number = $this->request->getQuery('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();
		$fees = $this->Fees
			->find('all')
			->where(['team_id' => $team->id]);

		return $this->response->withType("json")->withStringBody(json_encode([
			'members' => $query,
			'fees' => $fees
		]));
	}
	public function getTeamsOfUser() {

		$query = $this->Teams->find();
		$query->innerJoinWith('TeamMembers', function ($q) {
			$email = $this->request->getQuery('email');
			$user = $this->Users
				->find('all')
				->where(['email' => $email])->first();
			return $q->where(['TeamMembers.user_id' => $user->id, 'TeamMembers.is_admin' => 0]);
		});

		$teams = $this->Teams->find();
		$teams->innerJoinWith('TeamMembers', function ($q) {
			$email = $this->request->getQuery('email');
			$user = $this->Users
				->find('all')
				->where(['email' => $email])->first();
			return $q->where(['TeamMembers.user_id' => $user->id, 'TeamMembers.is_admin' => 1]);
		});

		/*$query = $this->Teams->find();
		$query->innerJoinWith('TeamMembers', function ($q) {
			$email = $this->request->getQuery('email');
			$user = $this->Users
				->find('all')
				->where(['email' => $email])->first();
			return $q->where(['TeamMembers.user_id' => $user->id]);
		});
		$email = $this->request->getQuery('email');
		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();


		$teams = $this->Teams->find();
		$teams->LeftJoin(
			['TeamMembers' => 'team_members'],
			['TeamMembers.team_id = Teams.id']);
		$teams->select(['Teams.id','Teams.team_name','Teams.currency_code','Teams.currency_symbol','Teams.connection_number','Teams.user_id', 'count_members' => 'count(TeamMembers.id)']) //count(*) AS records
		->where(['Teams.user_id' => $user->id])
			->group(['Teams.id']);*/

		return $this->response->withType("json")->withStringBody(json_encode([
			'teams' => $query,
			'admin' => $teams
		]));
	}

	public function deleteTeam() {
		$connection_number = $this->request->getData('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		if (empty($team)) {
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => "Team with such an ID does not exists!"
			]);
			return $this->response->withType("json")->withStringBody($resultJ);
		}
		$this->Fees->deleteAll(['team_id' => $team->id]);
		$this->TeamMembers->deleteAll(['team_id' => $team->id]);
		if ($this->Teams->delete($team)) {
			$resultJ = json_encode([
				'error' => false
			]);
		} else {
			$resultJ = json_encode([
				'error' => true
			]);
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}
}
