<?php

namespace dbbase\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_ext_src}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $customer_phone
 * @property integer $finance_order_channal_id
 * @property string $platform_name
 * @property string $channal_name
 * @property string $platform_ename
 * @property string $channal_ename
 * @property string $device_name
 * @property string $device_no
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerExtSrc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_ext_src}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_phone', 'finance_order_channal_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['platform_name', 'channal_name', 'platform_ename', 'channal_ename', 'device_name', 'device_no'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dbbase', '编号'),
            'customer_id' => Yii::t('dbbase', '客户'),
            'customer_phone' => Yii::t('dbbase', '客户手机'),
            'finance_order_channal_id' => Yii::t('dbbase', 'Finance Order Channal ID'),
            'platform_name' => Yii::t('dbbase', '平台名称'),
            'channal_name' => Yii::t('dbbase', '聚道名称'),
            'platform_ename' => Yii::t('dbbase', '平台拼音'),
            'channal_ename' => Yii::t('dbbase', '聚道拼音'),
            'device_name' => Yii::t('dbbase', '设备名称'),
            'device_no' => Yii::t('dbbase', '设备号码'),
            'created_at' => Yii::t('dbbase', '创建时间'),
            'updated_at' => Yii::t('dbbase', '更新时间'),
            'is_del' => Yii::t('dbbase', '是否逻辑删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerExtSrcQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerExtSrcQuery(get_called_class());
    }
}
