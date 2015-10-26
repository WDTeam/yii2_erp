<?php
namespace core\behaviors;

use yii\base\Behavior;
use common\components\Sms;
class SmslogBehavior extends Behavior
{

    public function events()
    {
        return [
            Sms::EVENT_SEND_AFTER=> 'sendAfter',
        ];
    }

    public function sendAfter($event)
    {
        $data = $event->sender->data;
        $res = \Yii::$app->db->createCommand()->insert('{{%general_smslog}}', [
            'general_smslog_mobiles'=>$data['pszMobis'],
            'general_smslog_msg'=>$data['pszMsg'],
            'general_smslog_res'=>$data['result'],
            'created_at'=>time(),
        ])->execute();
    }
}