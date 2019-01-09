<?php

namespace App\Controller;

use Cake\Event\Event;

class TeamMembersApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('Teams');
		$this->loadModel('TeamMembers');
		$this->loadModel('UsersFees');
		$this->loadModel('Fees');
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
		$email = $this->request->getData('email');
		$real_user = $this->request->getData('real_user');
		$connection_number = $this->request->getData('connection_number');

		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();

		if(empty($user)) {
			$hash = $this->hashSSHA('dcf0c766059e870b45db92278593519b');
			$user_data = [
				'name' => $name,
				'email' => $email,
				'facebook_json' => '',
				'password' => $hash["encrypted"],
				'salt' => $hash["salt"],
				'real_user' => $real_user,
			];

			$user = $this->Users->newEntity($user_data);

			if ($this->Users->save($user)) {

			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => 'User with this mail already exists in your team'
				]);
				return $this->response->withType("json")->withStringBody($resultJ);
			}
		}

		$is_admin = $this->request->getData('is_admin');
		$join_team = [
			'user_id' => $user->id,
			'team_id' => $team->id,
			'is_admin' => $is_admin
		];

		$team = $this->TeamMembers->newEntity($join_team);

		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($connection_number)) {
				try {
					if ($this->TeamMembers->save($team)) {
						$resultJ = json_encode([
							'error' => false
						]);
					} else {
						$resultJ = json_encode([
							'error' => true,
							'error_msg' => "Team does not exists!"
						]);
					}
				} catch (\Exception $e) {
					$resultJ = json_encode([
						'error' => true,
						'error_msg' => "This member is already in the team!"
					]);
				}

			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters (name or connection_number) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}
	public function getUsersInTeam() {
		$email = $this->request->getQuery('email');
		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		$connection_number = $this->request->getQuery('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$conditions = array(
			'user_id' => $user->id,
			'team_id' => $team->id
		);

		if ($this->TeamMembers->exists($conditions)){
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => "This member is already in the team!"
			]);
			return $this->response->withType("json")->withStringBody($resultJ);
		}

		$query = $this->Users->find()->select(['id', 'name','email'])->where(['email LIKE' =>'change%']);
		$query->innerJoinWith('TeamMembers', function ($q) {
			$connection_number = $this->request->getQuery('connection_number');
			$team = $this->Teams
				->find('all')
				->where(['connection_number' => $connection_number])->first();
			return $q->where(['TeamMembers.team_id' => $team->id]);
		});

		return $this->response->withType("json")->withStringBody(json_encode([
			'error' => false,
			'members' => $query
		]));
	}
	public function getAllUsers() {

		$connection_number = $this->request->getQuery('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$admin = $this->Users->find();
		$admin->innerJoinWith('TeamMembers', function ($q) {
			$connection_number = $this->request->getQuery('connection_number');
			$team = $this->Teams
				->find('all')
				->where(['connection_number' => $connection_number])->first();
			return $q->where(['TeamMembers.team_id' => $team->id,'TeamMembers.is_admin' => 1]);
		});

		$members = $this->Users->find();
		$members->innerJoinWith('TeamMembers', function ($q) {
			$connection_number = $this->request->getQuery('connection_number');
			$team = $this->Teams
				->find('all')
				->where(['connection_number' => $connection_number])->first();
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

		return $this->response->withType("json")->withStringBody(json_encode([
			'error' => false,
			'members' => $members,
			'admin' => $admin,
			'fees' => $fees
		]));
	}

	public function hashSSHA($password) {

		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}
	public function update() {
		$email = $this->request->getData('email');
		$update_id = $this->request->getData('update_id');

		$previous_user = $this->Users
			->find('all')
			->where(['id' => $update_id])->first();

		$joining_user = $this->Users
			->find('all')
			->where(['email' => $email])->first();

		$email_new = $joining_user->email;
		$previous_user->name = $joining_user->name;
		$previous_user->email = $email_new;
		$previous_user->encrypted_password = $joining_user->encrypted_password;
		$previous_user->salt = $joining_user->salt;
		$previous_user->facebook_json = $joining_user->facebook_json;
		$previous_user->created = $joining_user->created;
		$previous_user->modified = $joining_user->modified;

		if ($this->Users->delete($joining_user)) {
			$this->Users->save($previous_user);
			$resultJ = json_encode([
				'error' => false
			]);
		} else {
			$resultJ = json_encode([
				'error' => true,
				'error_msg' => $previous_user
			]);
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}
}
