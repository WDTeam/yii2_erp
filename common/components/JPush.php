<?php
namespace common\components;

use yii\base\Object;
use JPush\Model as M;
use JPush\JPushClient;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class JPush extends Object
{
    public $app_key;
    public $master_secret;
    
    public function init()
    {
        return parent::init();
    }
    
    public function push()
    {
        $br = '<br/>';
        $client = new JPushClient($this->app_key, $this->master_secret);
        
        $result = $client->push()
        ->setPlatform(M\all)
        ->setAudience(M\all)
        ->setNotification(M\notification('Hi, JPush'))
        ->send();
        echo 'Push Success.' . $br;
        echo 'sendno : ' . $result->sendno . $br;
        echo 'msg_id : ' .$result->msg_id . $br;
        echo 'Response JSON : ' . $result->json . $br;
        return $result;
    }
    
}