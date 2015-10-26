<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_pay_channel}}".
 *
 * @property integer $id
 * @property string $finance_pay_channel_name
 * @property integer $finance_pay_channel_rank
 * @property integer $finance_pay_channel_is_lock
 * @property integer $create_time
 * @property integer $is_del
 */
class FinancePayChannel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_pay_channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_pay_channel_rank','pay_channel_id', 'finance_pay_channel_is_lock', 'create_time', 'is_del'], 'integer'],
            [['finance_pay_channel_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * 获取数据列表
     * @return mixed
     */
    public static function getPayChannelByList()
    {
        //定义缓存名称
        $cacheName = 'payChannelList';

        //判断是否存在缓存
        if( $cache = Yii::$app->cache->get($cacheName) ){
            return $cache;
        }

        //生成缓存数据
        $data = self::find()->asArray()->all();
        self::createCache($cacheName,$data);

        //返回渠道名称
        return $data;
    }

    /**
     * 获取支付渠道名称
     * @param $id
     * @return mixed
     */
    public static function getPayChannelByName($id)
    {
        //定义缓存名称
        $cacheName = 'payChannel_'.$id;

        //判断是否存在缓存
        if( $cache = Yii::$app->cache->get($cacheName) ){
            return $cache['finance_pay_channel_name'];
        }

        //生成缓存数据
        $data = self::find()->where(['id'=>$id])->asArray()->one();
        self::createCache($cacheName,$data);

        //返回渠道名称
        return $data['finance_pay_channel_name'];
    }

    /**
     * 创建缓存
     * @param $name 缓存名称
     * @param $data 缓存数据
     */
    private static function createCache($name,$data)
    {
        \Yii::$app->cache->set($name, $data);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键id'), 
            'pay_channel_id' => Yii::t('boss', '支付渠道'),
            'finance_pay_channel_name' => Yii::t('boss', '渠道名称'),
            'finance_pay_channel_rank' => Yii::t('boss', '排序'),
            'finance_pay_channel_is_lock' => Yii::t('boss', '状态'),
            'create_time' => Yii::t('boss', '增加时间'),
            'is_del' => Yii::t('boss', '0 正常 1 删除'),
        ];
    }


    /**
     * 根据渠道id获取渠道名称
     * @date: 2015-10-9
     * @author: peak pan
     * @return:
     **/

    public static function get_pay_channel_info($pay_id)
    {
   
    	if($pay_id && $pay_id!=0){
    		$pay_info = FinancePayChannel::findOne($pay_id);
    		//var_dump($pay_info);exit;
    		//var_dump($pay_info->finance_pay_channel_name);exit;
    		return $pay_info->finance_pay_channel_name != NULL ? $pay_info->finance_pay_channel_name : '未知';
    	}else{
    		return '未知';
    	}
    	
    }

    
    public static function get_pay_channel_list(){
    	 $ordewhere['is_del']=0;
    	$ordewhere['finance_pay_channel_is_lock']=2;
    	 $payatainfo=FinancePayChannel::find()->where($ordewhere)->asArray()->all();	
    	foreach ($payatainfo as $errt){
    		$tyd[]=$errt['id'];
    		$tydtui[]=$errt['finance_pay_channel_name'];
    	}
    	$tyu= array_combine($tyd,$tydtui);
    	 
    	return $tyu;
    }
    
    

}
