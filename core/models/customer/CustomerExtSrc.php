<?php

namespace core\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_ext_src}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $platform_name
 * @property string $channal_name
 * @property string $device_name
 * @property string $device_no
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerExtSrc extends \common\models\CustomerExtSrc
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
            [['customer_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['platform_name', 'channal_name', 'device_name', 'device_no'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '编号'),
            'customer_id' => Yii::t('boss', '客户'),
            'platform_name' => Yii::t('boss', '平台名称'),
            'channal_name' => Yii::t('boss', '聚道名称'),
            'device_name' => Yii::t('boss', '设备名称'),
            'device_no' => Yii::t('boss', '设备号码'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }

    /**
     * 获取最初来源
     */
    public static function getFirstSrc($customer_id){
        $customerExtSrc = self::find()->where(['customer_id'=>$customer_id])->orderBy('created_at asc')->one();
        return $customerExtSrc == NULL ? false : $customerExtSrc;
    }
}
