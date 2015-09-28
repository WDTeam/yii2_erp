<?php
/**
 * @see http://docs.jpush.cn/display/dev/Push-API-v3#Push-API-v3-%E6%8E%A8%E9%80%81%E5%AF%B9%E8%B1%A1
 */
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
    
    public $client;
    
    public function init()
    {
        $this->client = new JPushClient($this->app_key, $this->master_secret);
        return parent::init();
    }

    /**
     * 推送消息,推送给指定阿姨需要和客户端统一registration_id规则后方可实现。
     * @param string $msg 消息内容
     * @return \JPush\Model\PushResponse
     */
    public function push($msg='hi')
    {
        $result = $this->client->push()
        ->setPlatform(M\all)
        ->setAudience(M\all)
        ->setNotification(M\notification($msg))
        ->send();
        return $result;
    }
    
    /**
     * 获取推送统计
     */
    public function getReport()
    {
        $msg_ids = '3800995500,3264017765';
        $result = $this->client->report($msg_ids);
        return $result;
    }
    
}