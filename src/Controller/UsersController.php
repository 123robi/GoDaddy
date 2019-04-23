<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Error\Debugger;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Teams');
		$this->loadComponent('Auth', [
			'loginRedirect' => [
				'controller' => 'teams',
				'action' => 'index'
			]
		]);
	}

	public function isAuthorized($user)
	{
		if (in_array($this->request->getParam('action'), ['register1','register2','login','logout','add', 'setPassword'])) {
			return true;
		}

		if (in_array($this->request->getParam('action'), ['delete'])) {
			$teamId = $this->request->getParam('team_id');
			if ($this->TeamMembers->isAdmin($teamId, $user['id'])) {
				return true;
			}
		}

		return false;
	}

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow(['register1','register2', 'logout']);
	}

	public function register1() {
		$user = $this->Users->newEntity();

		if ($this->request->is('post')) {
			$exists = $this->Users->find()->where(['email' => $this->request->getData()['email'], 'real_user' => 1])->first();
			if (empty($exists)) {
				return $this->redirect(['action' => 'register2','email' => $this->request->getData()['email']]);
			} else {
				$this->Flash->error(__('The user with this email already exists'));

				return $this->redirect(['action' => 'login']);
			}
		}
		$this->set('user', $user);
	}

	public function register2() {
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$exists = $this->Users->find()->where(['email' => $this->request->getQuery('email'), 'real_user' => 0])->first();
			if (empty($exists)) {
				$user = $this->Users->patchEntity($user, $this->request->getData());
				$user->email = $this->request->getQuery('email');
				$user->real_user = 1;
				if ($this->Users->save($user)) {
					$this->Flash->success(__('The user has been saved.'));

					return $this->redirect(['action' => 'login']);
				}
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			} else {
				$exists->name = $this->request->getData('name');
				$exists->password = $this->request->getData('password');
				$exists->phone_number = $this->request->getData('phone_number');
				$exists->address = $this->request->getData('address');
				$exists->real_user = 1;
				if ($this->Users->save($exists)) {
					$this->Flash->success(__('The user has been saved.'));

					return $this->redirect(['action' => 'login']);
				}
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
		$this->set('user', $user);
	}

	public function login()
	{
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Flash->error(__('Invalid username or password, try again'));
		}
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}

	public function setPassword() {
		$user = $this->Users->get($this->Auth->user('id'));
		if (!empty($user->phone_number) && !empty($user->address)) {
			return $this->redirect(['controller' => 'Teams' , 'action' => 'index']);
		}
		$user->password='';

		if ($this->request->is(['patch', 'post', 'put'])) {
			$user->password = $this->request->getData('password');
			$user->phone_number = $this->request->getData('phone_number');
			$user->address = $this->request->getData('address');
			if ($this->Users->save($user)) {
				return $this->redirect(['controller' => 'Teams' , 'action' => 'index']);
			}
		}
		$this->set(compact('user'));
	}
	/**
	 * @return \Cake\Http\Response|null
	 */
	public function index()
	{
		return $this->redirect(['controller' => 'Teams' , 'action' => 'index']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$user = $this->Users->get($id, [
			'contain' => ['Fees', 'TeamMembers', 'Teams']
		]);

		$this->set('user', $user);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
		}
		$fees = $this->Users->Fees->find('list', ['limit' => 200]);
		$this->set(compact('user', 'fees'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$user = $this->Users->get($id, [
			'contain' => ['Fees']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
		}
		$fees = $this->Users->Fees->find('list', ['limit' => 200]);
		$this->set(compact('user', 'fees'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
