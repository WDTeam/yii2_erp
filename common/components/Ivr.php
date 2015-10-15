<?php
/**
 * 天润接口文档
        发起者：合作方
	接受者：天润融通
	接口类型：HTTP/HTTPS 接口支持HTTPS，为了安全建议使用HTTPS协议
	调用方式：GET/POST
	返回格式：JSON格式数据
	URL规范：
https://api.vlink.cn/interface/open/v1/webcall?tel=&appId=&sign=&timestamp=&${name}=

2.1. 参数说明
参数	要求	描述	备注
appId	必选	应用编号	由天润生成并提供
timestamp	必选	当前时间戳	linux时间戳，时间戳有效时间30分钟
sign	必选	鉴权	md5({app_id}{token}{timestamp})
其中token由天润生成并提供
验证参数，32位全小写
tel
必选	电话号码	固话带区号，手机不加0；
固话带分机的以'-'分隔；
支持多个号码，多个直接用英文逗号’,’分隔；
多号码时为依次呼叫，直到接通
userField	可选	用户自定义字段	长度不超过250个字节。1个汉字是3字节。此字段保存到通话记录userField字段，后续接口查询使用
uniqueId	可选	提交标识	长度不超过50个字节。1个汉字是3字节。此字段保存到通话记录request_uniqueId字段，后续接口查询使用
默认情况下为系统生成
timeout	可选	呼叫客户超时时间	秒数，如果传入参数，参数值必须为大于0小于等于60的整数；不传入，默认为30s
delay	可选	延迟时长	秒数，延迟多少秒呼叫，只在sync=0时生效，范围为大于0小于等于60的整数
${name}	可选	自定义参数（支持多个）	参数值必须使用utf-8编码进行urlencode
参数值不能含有逗号’,’，如果有会被转换为中文逗号’，’
所有参数需要预先通过技术支持人员进行设置，否则视为无效
如：验证码业务一般用参数code，语音短信一般用参数message

2.2. 返回值
此接口返回JSON格式数据。
示例：
{"result":"0","uniqueId":"be3ee65f8334a75c186875e92a81cd81","clid":"4006869009","description":"提交成功"}
参数列表:
字段	描述	说明
result	提交结果	0-成功；1-失败
description	描述	详情见下方列表
uniqueId	提交标识	当企业传参为空时，系统自动生成唯一值填充
clid	外显号码	呼叫客户时的外显号码

description列表:
description	说明
提交失败，参数tel不能为空	
提交失败，参数tel格式不正确	标准格式为手机或带区号的固话
提交失败，userField不能超过250个字符	
提交失败，uniqueId不能超过50个字符	
提交失败，参数timeout格式不正确	1-60的整数
提交失败，参数delay格式不正确	1-60的整数
提交失败，参数xxx错误	自定义参数错误，如定义了code参数但没有传参
超过企业并发限制	每个企业存在提交并发限制
提交成功	
 
 */
namespace common\components;

use yii\base\Component;
use yii\base\Event;
use yii\web\Application;
use core\behaviors\IvrlogBehavior;
use yii\web\HttpException;
use yii\base\ExitException;
class Ivr extends Component
{
    const IVR_URL = 'https://api.vlink.cn/interface/open/v1/webcall';
    const EVENT_SEND_AFTER = 'ivrSendAfter';
    const EVENT_CALLBACK = 'ivrCallback';
    /**
     * 必选	应用编号	由天润生成并提供
     */
    public $app_id;
    public $token;
    
    public $cb_data=[];
    public $request_data;
    
    public function init(){
        
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>IvrlogBehavior::className(),
            ]
        ];
    }
    /**
     * 发送语音播报
     * @param string|int $telephone 电话号码
     * @param string $orderId 订单ID
     * @param string $orderMessage 订单消息
     */
    public function send($telephone, $orderId, $orderMessage)
    {
        $sign = md5($this->app_id.$this->token.time());
        $ivrarr = [
            'tel' => $telephone,				//手机号  测试手机号13311009484
            'appId'  => $this->app_id,		    //APPID
            'sign' => $sign,			        //签名
            'timestamp' => time(),
            'orderMessage' => $orderMessage,
            'orderId' => $orderId
        ];
        $res = $this->request($ivrarr);
        $this->request_data = $res = array_merge($ivrarr, (array)json_decode($res,true));

        if(!isset($res['result'])){
            throw new \ErrorException('请求错误');
        }
        $this->trigger(self::EVENT_SEND_AFTER);
        return $res;
    }
    /**
     * 回调处理
     * @param string $telephone 11位的手机号码；例：138001380000
     * @param string $orderId   订单号，例：4563
     * @param int|null $press   1)、如果没按键或者按的不是1、2;press=0
                                2)、按1的话,press=1
                                3）、电话通了，直接关机，没有接听;press返回空
                                4)、按2 ，只返回一次，在挂机后返回，按2的话;press=2
     * @param int $result   1)、20-TTS合成失败
                            2)、21-未接通
                            3)、22-接通、系统应答
                            4)、23-接通、转接未接通
                            5)、24-接通、转接接通
     * @param array $postType   1)、1代表按键
                                2)、2代表挂机
    $telephone, $orderId, $press, $result, $postType=null
     */
    public function callback($data)
    {
        $this->cb_data = $data;
        $this->trigger(self::EVENT_CALLBACK);
        return $data;
    }
    
    public function request($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //获取的信息以文件流的形式返回

        curl_setopt($ch, CURLOPT_POST, 1);//发送post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));// Post提交的数据包

        curl_setopt($ch, CURLOPT_URL, self::IVR_URL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, '1jiajie');
        
        $output = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
         
        return $output;
    }
    
}