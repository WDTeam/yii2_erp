<?php
/**
-999	服务器内部错误
-10001	用户登陆不成功
-10002	提交格式不正确
-10003	用户余额不足
-10004	手机号码不正确
-10005	计费用户帐号错误
-10006	计费用户密码错
-10007	账号已经被停用
-10008	账号类型不支持该功能
-10009	其它错误
-10010	企业代码不正确
-10011	信息内容超长
-10012	不能发送联通号码
-10013	操作员权限不够
-10014	费率代码不正确
-10015	服务器繁忙
-10016	企业权限不够
-10017	此时间段不允许发送
-10018	经销商用户名或密码错
-10019	手机列表或规则错误
-10021	没有开停户权限
-10022	没有转换用户类型的权限
-10023	没有修改用户所属经销商的权限
-10024	经销商用户名或密码错
-10025	操作员登陆名或密码错误
-10026	操作员所充值的用户不存在
-10027	操作员没有充值商务版的权限
-10028	该用户没有转正不能充值
-10029	此用户没有权限从此通道发送信息
-10030	不能发送移动号码
-10031	手机号码(段)非法
-10032	用户使用的费率代码错误
-10033	非法关键词
*/
namespace dbbase\components;

/**
 * 添加配置
    'sms'=>[
        'class'=>'common\components\Sms',
        'userId'=>'J02356',
        'password'=>'556201',
    ],
    
 * 使用: Yii::$app->sms->send('手机号，逗号分隔', '短信内容', '一次并发数,可选');
 */
use yii\base\Component;
use core\behaviors\SmslogBehavior;
class Sms extends Component
{
    public $userId='';
    public $password='';
    
    const EVENT_SEND_AFTER = 'smsSendAfter';
    
    public function behaviors()
    {
        return [
            [
                'class'=>SmslogBehavior::className(),
            ]
        ];
    }
    public $data;
    /**
     * 发短信
     * @param string $mobiles 手机号，逗号分隔，可以是单个号码，最多100个
     * @param string $msg 内容，120字以内
     * @param number $iMobiCount    并发数量，最多100
     * @return string 信息编号，如：-8485643440204283743或1485643440204283743，可根据返回值位数判断提交是否成功，如果返回的流水大于10位小于25位为提交成功
     */
    public function send($mobiles, $msg, $iMobiCount=1)
    {
        $this->data = [
            'userId'=>$this->userId,
            'password'=>$this->password,
            'pszMobis'=>$mobiles,
            'pszMsg'=>$msg,
            'iMobiCount'=>$iMobiCount,
            'pszSubPort'=>'*'
        ];
        $reqdata = http_build_query($this->data);
        $url='http://ws.montnets.com:9006/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?'.$reqdata;
        $ch = \curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $data =  curl_exec($ch);
        curl_close($ch);
        $result = (array)simplexml_load_string($data);
        $result = isset($result)&&isset($result[0])?$result[0]:$data;
        $this->data['result'] = $result;
        $this->trigger(self::EVENT_SEND_AFTER);
        return $result;
    }
}