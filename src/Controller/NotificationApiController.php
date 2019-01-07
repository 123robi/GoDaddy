<?php

namespace App\Controller;


use Cake\Event\Event;

class NotificationApiController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadModel('Users');
		$this->loadModel('Teams');
        $this->loadModel('Events');
        $this->loadModel('Places');
        $this->loadModel('TeamMembers');
	}

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow();
	}

	public function sendEventNotification()
    {
        $id = $this->request->getData('id');
        $tokens = array();
        $event = $this->Events
            ->find('all')
            ->where(['id' => $id])->first();

        $location = $this->Places
            ->find('all')
            ->where(['id' => $event->place_id])->first();

        $members = $this->Users->find();
        $members->innerJoinWith('TeamMembers', function ($q) {
            $id = $this->request->getData('id');
            $event = $this->Events
                ->find('all')
                ->where(['id' => $id])->first();
            $team = $this->Teams
                ->find('all')
                ->where(['id' => $event->team_id])->first();
            return $q->where(['TeamMembers.team_id' => $team->id]);
        });
        foreach ($members as $row) {
            if (!is_null($row->fcm)) {
                $tokens[] = $row->fcm;
            }
        }

        $message = ["event" => $event, "place" => $location];
        $this->send_notification($tokens, $message);
    }
    public function sendMessage() {
        $email = $this->request->getData('email');
        $title = $this->request->getData('title');
        $messagePost = $this->request->getData('message');
        $tokens = array();
        $user = $this->Users
            ->find('all')
            ->where(['email' => $email])->first();
        $tokens[] = $user->fcm;
        $message = ["message" => $messagePost, "title" => $title];

       if ($this->send_notification($tokens, $message)) {
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
	function send_notification ($tokens, $message)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';

        $registration_ids = array_values($tokens);

        $numTokens = count($registration_ids);
        if($numTokens == 1){
            $fields = array(
                'to' => $registration_ids[0],
                'data' => $message,
            );
        } else {
            $fields = array(
                'registration_ids' => $registration_ids,
                'data' => $message,
            );
        }
		$headers = array(
			'Authorization:key = AAAAo-5JUY0:APA91bHZgGEMIwVBk-4SAZuB_N2qe81oH-qAMIyYWf4pnAFFBlhf3mG7MA8IQYyaZVH9QRJv3UXPsej9oIdlZK203HIrBH68jqVbN-zhdCYlDmskw2jAUwHKDvUq4BhPkUMt1LSWZfys',
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;

	}

}
