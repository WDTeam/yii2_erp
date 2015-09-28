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
        $this->client = new JPushClient($this->app_key, $this->master_secret);
        return parent::init();
    }

    /**
     * 推送消息
     * @param string $msg 消息内容
     * @return \JPush\Model\PushResponse
     */
    public function push($msg)
    {
        $result = $this->client->push()
        ->setPlatform(M\all)
        ->setAudience(M\all)
        ->setNotification(M\notification($msg))
        ->send();
        return $result;
    }
    
}