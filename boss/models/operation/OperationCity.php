<?php
namespace boss\models\operation;

use Yii;
use core\models\operation\OperationCity;
/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property string $operation_city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCity extends OperationCity
{
    /**
     * @inheritdoc
     */
//    public static function tableName()
//    {
//        return '{{%operation_city}}';
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_city_is_online', 'created_at', 'updated_at', 'city_id', 'province_id'], 'integer'],
            [['city_name','province_name'], 'string', 'max' => 30],
            [['city_name','province_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'province_id' => Yii::t('app', '省份编号'),
            'province_name' => Yii::t('app', '省份名称'),
            'city_id' => Yii::t('app', '城市编号'),
            'city_name' => Yii::t('app', '城市名称'),
            'operation_city_is_online' => Yii::t('app', '上线状态'),//（1为上线，2为下线）
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
            'category' => Yii::t('app', '服务品类'),
            'shopdistrict' => Yii::t('app', '商圈'),
        ];
    }
    
    
}
