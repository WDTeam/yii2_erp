<?php
/**
 * 极光推送
 * @see http://docs.jpush.cn/display/dev/Push-API-v3#Push-API-v3-%E6%8E%A8%E9%80%81%E5%AF%B9%E8%B1%A1
 * 客户端安装后，测试用到的账号：13401096964  密码：123456
 */
namespace dbbase\components;

use yii\base\Object;
use JPush\Model as M;
use JPush\JPushClient;
use JPush\Exception\APIRequestException;
use yii\base\Component;
use yii\base\Event;
use yii\helpers\ArrayHelper;

class JPush extends Component
{
    public $app_key;
    public $master_secret;
    
    public $client;
    public $data;
    
    const EVENT_PUSH_AFTER = 'event_push_after';
    
    public function init()
    {
        $this->client = new JPushClient($this->app_key, $this->master_secret);
        return parent::init();
    }
    /**
     * 给指定tags推送
     * iOS Notification 长度限制提升为2000字节；
     * iOS Message 长度限制修改为1000字节；
     * Android,WinPhone平台的Notification+Message长度限制修改为1000字节。
     * @param array $tags ['wworker_uuu_ttt2','worker_ldg_test', 'wworker_uuu_ttt']
     * @param string $msg
     * @param object $extras 附加发送的自定义数据
     */
    public function push($tags, $msg, $extras=[])
    {
        try{
            $result = $this->client->push()
            ->setPlatform(M\platform('ios', 'android'))
            ->setAudience(M\Audience(M\Tag($tags)))
            ->setNotification(M\notification($msg))
            ->send();
        }catch(APIRequestException $e){
            $result = $e;
        }
        $this->data = [
            'tags'=>$tags,
            'msg'=>$msg,
            'extras'=>$extras,
            'result'=>ArrayHelper::toArray($result),
        ];
        $this->trigger(self::EVENT_PUSH_AFTER);
        return $result;
    }
    /**
     * 复杂应用完整信息push
     * @param array $tags 标签
     * @param string $msg 消息内容
     * @param array $extras 自定义字段
     * @param string $category 消息分类
     */
    public function fullPush($tags, $title, $msg, $extras=[], $category=null)
    {
        try{
            $result = $this->client->push()
            ->setPlatform(M\platform('ios', 'android'))
            ->setAudience(M\Audience(M\Tag($tags)))
            ->setNotification(M\notification($title,
                M\android($title, $title, null, $extras),
                M\ios($title, $title, null, true, $extras, $category)
            ))
            ->setMessage(M\message($msg, $title, $category, $extras))
            ->send();
        }catch(APIRequestException $e){
            $result = $e;
        }
        $this->data = [
            'tags'=>$tags,
            'msg'=>$msg,
            'extras'=>$extras,
            'title'=>$title,
            'category'=>$category,
            'result'=>ArrayHelper::toArray($result),
        ];
        $this->trigger(self::EVENT_PUSH_AFTER);
        return $result;
    }
    /**
     * 推送消息,不建议使用,仅供参考
     * @param string $msg 消息内容
     * @return \JPush\Model\PushResponse
     */
    public function pushAll($msg='hi')
    {
        $result = $this->client->push()
        ->setPlatform(M\all)
        ->setAudience(M\all)
        ->setNotification(M\notification($msg))
        ->send();
        return $result;
    }
    /**
     * 给指定android推送
     * @param array $tags 客户端标签    如给指定阿姨端发送  eg: ['worker_1','worker_2']
     * @param string $msg
     * @param array $extras 附加发送的自定义数据
     */
    public function push2android($tags, $msg, $extras=[])
    {
        $result = $this->client->push()
        ->setPlatform(M\platform("android"))
        ->setAudience(M\Audience(M\Tag($tags)))
        ->setNotification(M\notification(null, M\android($msg, '', 1, $extras)))
        ->send();
        return $result;
    }
    /**
     * 给指定ios推送
     * @param array $tags 客户端标签    如给指定阿姨端发送  eg: 'worker_1,worker_2'
     * @param string $msg
     * @param array $extras 附加发送的自定义数据
     */
    public function push2ios($tags, $msg, $extras=[])
    {
        $result = $this->client->push()
        ->setPlatform(M\platform("ios"))
        ->setAudience(M\Audience(M\Tag($tags)))
        ->setNotification(M\notification(null, M\ios($msg, '', 1, $extras)))
        ->send();
        return $result;
    }
    
    /**
     * 获取推送统计
     * @param string $msg_ids 消息id,逗号分隔
     */
    public function getReport($msg_ids)
    {
        $msg_ids = '3800995500,3264017765';
        $result = $this->client->report($msg_ids);
        return $result;
    }
    /**
     * Jpush回调
     * use:\Yii::$app->jpush->callback($data);
     * @param unknown $params
     */
    public function callback($params)
    {
        
    }
}