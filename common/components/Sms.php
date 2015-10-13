<?php
namespace common\components;

/**
 * 添加配置
    'sms'=>[
        'class'=>'common\components\Sms',
        'userId'=>'J02356',
        'password'=>'556201',
    ],
    
 * 使用: Yii::$app->sms->send('手机号，逗号分隔', '短信内容', '一次并发数,可选');
 */
use yii\base\Object;
class Sms extends Object
{
    public $userId='';
    public $password='';
    
    /**
     * 发短信
     * @param string $mobiles 手机号，逗号分隔，可以是单个号码，最多100个
     * @param string $msg 内容，120字以内
     * @param number $iMobiCount    并发数量，最多100
     * @return string 信息编号，如：-8485643440204283743或1485643440204283743，可根据返回值位数判断提交是否成功，如果返回的流水大于10位小于25位为提交成功
     */
    public function send($mobiles, $msg, $iMobiCount=1)
    {
        $reqdata = http_build_query([
            'userId'=>$this->userId,
            'password'=>$this->password,
            'pszMobis'=>$mobiles,
            'pszMsg'=>$msg,
            'iMobiCount'=>$iMobiCount,
            'pszSubPort'=>'*'
        ]);
        $url='http://ws.montnets.com:9006/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?'.$reqdata;
        $ch = \curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $data =  curl_exec($ch);
        curl_close($ch);
        $result = (array)simplexml_load_string($data);
        return isset($result)&&isset($result[0])?$result[0]:$data;
    }
}