<?php

namespace App\Controller;


use Cake\Event\Event;

class PaymentsApiController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('Teams');
        $this->loadModel('Payments');
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
        $accNumber = $this->request->getData('account_number');
        $accPrefix = $this->request->getData('account_prefix');
        $bankCode = $this->request->getData('bank_code');
        $connection_number = $this->request->getData('connection_number');

        $team = $this->Teams
            ->find('all')
            ->where(['connection_number' => $connection_number])->first();
        $payment_data = [
            'account_number' => $accNumber,
            'account_prefix' => $accPrefix,
            'bank_code' => $bankCode,
            'team_id' => $team->id
        ];
        $payment = $this->Payments->newEntity($payment_data);

        if ($this->request->is('post')) {
            if (!is_null($accNumber) && !is_null($connection_number)) {
                if ($this->Payments->save($payment)) {
                    $resultJ = json_encode([
                        'error' => false,
                        'payment' => $payment,
                    ]);
                } else {
                    $resultJ = json_encode([
                        'error' => true,
                        'error_msg' => "Error"
                    ]);
                }
            } else {
                $resultJ = json_encode([
                    'error' => true,
                    'error_msg' => "Required parameters (account_number, account_prefix, bank_code or connection_number) is missing!",
                ]);
            }
        }
        return $this->response->withType("json")->withStringBody($resultJ);
    }

    public function getQrCode() {
        $connection_number = $this->request->getData('connection_number');
        $cost = $this->request->getData('cost');
        $currency_symbol = $this->request->getData('symbol');
        $message = $this->request->getData('message');
        $message =  urlencode($message);
        $team = $this->Teams
            ->find('all')
            ->where(['connection_number' => $connection_number])->first();

        $payment = $this->Payments
            ->find('all')
            ->where(['team_id' => $team->id])->first();
        $url = "https://api.paylibo.com/paylibo/generator/czech/image?accountNumber=" . $payment->account_number .
            "&bankCode=" . $payment->bank_code .
            "&amount=" . sprintf('%0.2f', $cost) .
            "&currency=" . $currency_symbol .
            "&message=" . $message;
        return $this->response->withType("json")->withStringBody($url);

    }

}
