<?php

namespace common\models\sq_ejiajie_v2;

use Yii;

/**
 * This is the model class for table "user_info".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gender
 * @property integer $free_pay_hours
 * @property integer $is_block
 * @property string $block_date
 * @property string $create_time
 * @property double $charge_money
 * @property double $reward_money
 * @property integer $user_type
 * @property integer $level
 * @property string $user_src
 * @property string $city_name
 * @property string $expect_service
 * @property integer $discount
 * @property integer $shop_id
 * @property integer $pop_community_id
 * @property integer $join_active_id
 * @property string $wanted_type
 * @property string $street
 * @property double $already_consum
 * @property string $extend_info
 * @property string $channel
 * @property string $education
 * @property string $birthday
 * @property integer $parent_user_id
 * @property string $gift_record
 * @property string $telphone
 * @property string $email
 * @property string $smscode
 * @property string $device_token
 * @property string $mac_add
 * @property integer $device_type
 * @property string $update_time
 * @property string $weixin_id
 * @property integer $is_order_response
 * @property integer $is_order_recall
 * @property integer $user_roll
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbv2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender', 'free_pay_hours', 'is_block', 'user_type', 'level', 'discount', 'shop_id', 'pop_community_id', 'join_active_id', 'parent_user_id', 'telphone', 'device_type', 'is_order_response', 'is_order_recall', 'user_roll'], 'integer'],
            [['block_date', 'create_time', 'birthday', 'update_time'], 'safe'],
            [['charge_money', 'reward_money', 'already_consum'], 'number'],
            [['name', 'user_src'], 'string', 'max' => 32],
            [['city_name', 'wanted_type', 'channel'], 'string', 'max' => 16],
            [['expect_service', 'street'], 'string', 'max' => 64],
            [['extend_info'], 'string', 'max' => 128],
            [['education'], 'string', 'max' => 8],
            [['gift_record', 'weixin_id'], 'string', 'max' => 255],
            [['email', 'mac_add'], 'string', 'max' => 50],
            [['smscode'], 'string', 'max' => 20],
            [['device_token'], 'string', 'max' => 100],
            [['telphone'], 'unique'],
            [['telphone'], 'required'],
            [['telphone'], 'match','pattern'=>'/^1[0-9]{10}$/']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'gender' => '男的为1，女的为0',
            'free_pay_hours' => '免单小时数',
            'is_block' => '是否黑名单',
            'block_date' => '阻塞时间',
            'create_time' => '创建时间',
            'charge_money' => '充值钱',
            'reward_money' => '返的钱',
            'user_type' => '用户等级，0 是非会员',
            'level' => '用户级别，0 是没级别，1是银卡，2是金卡，3是铂金卡',
            'user_src' => '会员来源',
            'city_name' => '城市',
            'expect_service' => '希望享受的服务',
            'discount' => '卡折扣',
            'shop_id' => '所属门店',
            'pop_community_id' => '推广小区',
            'join_active_id' => '参与活动',
            'wanted_type' => '服务类型',
            'street' => '街道',
            'already_consum' => '已消费钱数',
            'extend_info' => '用户需求',
            'channel' => '渠道',
            'education' => '文化程度',
            'birthday' => '出生日期',
            'parent_user_id' => '可以用谁的卡',
            'gift_record' => '实物赠送记录',
            'telphone' => '用户电话',
            'email' => '邮件',
            'smscode' => '验证码',
            'device_token' => '手机token',
            'mac_add' => 'Mac Add',
            'device_type' => 'Device Type',
            'update_time' => '更新时间',
            'weixin_id' => '用户的微信号',
            'is_order_response' => '是否需要订单响应 0=>是 1=>否',
            'is_order_recall' => '是否需要订单回访 0=>是 1=>否',
            'user_roll' => '用户名单',
        ];
    }
}
