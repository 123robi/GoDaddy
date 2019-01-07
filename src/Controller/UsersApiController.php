<?php

namespace App\Controller;


use Cake\Event\Event;

class UsersApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('Images');
		$this->loadModel('Teams');
	}

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow();
	}
	public function add() {
		$name = $this->request->getData('name');
		$email = $this->request->getData('email');
		$phone_number = $this->request->getData('phone_number');
		$address = $this->request->getData('address');
		$password  = $this->request->getData('password');
		$facebook_json = $this->request->getData('facebook_json');
		$real_user = $this->request->getData('real_user');
		$fcm = $this->request->getData('fcm');

		//$hash = $this->hashSSHA($password);

		$user_data = [
			'name' => $name,
			'email' => $email,
			'phone_number' => $phone_number,
			'address' => $address,
			'facebook_json' => $facebook_json,
			'password' => $password,
			//'password' => $hash["encrypted"],
			'salt' => '123asdas',
			'real_user' => $real_user,
			'fcm' => $fcm
		];
		$user = $this->Users->newEntity($user_data);

		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($email) && !is_null($password)) {
				if ($this->Users->save($user)) {
					$resultJ = json_encode([
						'error' => false,
						'user' => $user,
					]);
				} else {
					if (!is_null($facebook_json)) {
						$user = $this->Users
							->find('all')
							->where(['email' => $email])->first();

						$user->facebook_json = $facebook_json;
						if (!is_null($fcm)) {
                            $user->fcm = $fcm;
                        }
						$this->Users->save($user);
					}
					$resultJ = json_encode([
						'error' => true,
						'error_msg' => "User already existed with " . $this->request->getData('email'),
						'user' => $user_data,
					]);
				}
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters (name, email or password) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function login() {
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				$resultJ = json_encode([
					'error' => false,
					'user' => $user,
				]);
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Login credentials are wrong. Please try again!"
				]);
			}
			return $this->response->withType("json")->withStringBody($resultJ);
		}
	}

	public function changePassword() {
		$email = $this->request->getData('email');
		$password = $this->request->getData('password');
		$new_password = $this->request->getData('new_password');

		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$user = $this->Users
					->find('all')
					->where(['email' => $email])->first();
				$user->password = $new_password;
				$this->Users->save($user);
				$resultJ = json_encode([
					'error' => false,
				]);
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Your current password is incorrect!"
				]);
			}
			return $this->response->withType("json")->withStringBody($resultJ);
		}
	}

	public function changePasswordFacebook() {
		$email = $this->request->getData('email');
		$new_password = $this->request->getData('new_password');

		if ($this->request->is('post')) {
			if (!is_null($email) && !is_null($new_password)) {
				$user = $this->Users
					->find('all')
					->where(['email' => $email])->first();
				$user->password = $new_password;

				$this->Users->save($user);
				$resultJ = json_encode([
					'error' => false,
				]);
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters email or password is missing!"
				]);
			}
		}

		return $this->response->withType("json")->withStringBody($resultJ);
	}


	public function changeUserDetails() {

		$name = $this->request->getData('name');
		$email = $this->request->getData('email');
		$address = $this->request->getData('address');
		$phone_number = $this->request->getData('phone_number');

		if ($this->request->is('post')) {
			if (!is_null($name) && !is_null($address) && !is_null($phone_number)) {
				$user = $this->Users
					->find('all')
					->where(['email' => $email])->first();

				$user->name = $name;
				$user->address = $address;
				$user->phone_number = $phone_number;

				$this->Users->save($user);
				$resultJ = json_encode([
					'error' => false,
					'user' => $user,
				]);
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters email or password is missing!"
				]);
			}
		}

		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function exists() {
		$email = $this->request->getData('email');
		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		if(empty($user)) {
			$resultJ = json_encode([
				'exists' => false,
			]);
		} else {
			if ($user->real_user == 0) {
				$resultJ = json_encode([
					'exists' => true,
					'real_user' => false
				]);
			} else {
				$resultJ = json_encode([
					'exists' => true,
					'real_user' => true
				]);
			}

		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}

	public function update() {
		$email = $this->request->getData('email');
		$password = $this->request->getData('password');
		if ($this->request->is('post')) {
			if (!is_null($email) && !is_null($password)) {
				$user = $this->Users
					->find('all')
					->where(['email' => $email])->first();

				$user->password = $password;
				$user->real_user = 1;

				$this->Users->save($user);
				$resultJ = json_encode([
					'error' => false,
					'user' => $user
				]);
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters email, password or reaL_user is missing!"
				]);
			}
		}

		return $this->response->withType("json")->withStringBody($resultJ);
	}
	public function test() {
		$image = $this->request->getData('image');
		$email = $this->request->getData('email');
		$connection_number = $this->request->getData('connection_number');

		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();

		$image_data = [
			'user_id' => $user->id,
			'team_id' => $team->id,
			'image' => $image
		];
		//delete all images that are connected to this user
		$this->Images->deleteAll(['user_id' => $user->id]);
		$user = $this->Images->newEntity($image_data);
		if ($this->request->is('post')) {
			if (!is_null($image) ) {
				if ($this->Images->save($user)) {
					$resultJ = json_encode([
						'error' => false,
						'user' => $user,
					]);
				} else {
					$resultJ = json_encode([
						'error' => true,
						'user' => $user,
					]);
				}
			} else {
				$resultJ = json_encode([
					'error' => true,
					'error_msg' => "Required parameters (name, email or password) is missing!",
				]);
			}
		}
		return $this->response->withType("json")->withStringBody($resultJ);
	}
	public function insertImage() {
		$image = $this->request->getData('image');
		$email = $this->request->getData('email');
		$upload_path = $_SERVER['DOCUMENT_ROOT']."/images/".$email.".jpg";
		if(file_put_contents($upload_path, base64_decode($image))) {
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

	public function getImages() {
		$connection_number = $this->request->getQuery('connection_number');
		$team = $this->Teams
			->find('all')
			->where(['connection_number' => $connection_number])->first();
		$images = $this->Images
			->find('all')
			->where(['team_id' => $team->id])->first();
		return $this->response->withType("json")->withStringBody((string)$images->image);
	}
	public function updateFcm() {
		$email = $this->request->getData('email');
		$fcm = $this->request->getData('fcm');
		$user = $this->Users
			->find('all')
			->where(['email' => $email])->first();
		$user->fcm = $fcm;
		$this->Users->save($user);
	}

	public function hashSSHA($password) {

		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}

	public function checkhashSSHA($salt, $password) {

		$hash = base64_encode(sha1($password . $salt, true) . $salt);

		return $hash;
	}
}
