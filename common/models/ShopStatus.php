<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shop_status}}".
 *
 * @property integer $id
 * @property integer $status_number
 * @property string $cause
 * @property integer $created_at
 * @property string $model_name
 * @property integer $status_type
 */
class ShopStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_number'], 'required'],
            [['status_number', 'created_at', 'status_type'], 'integer'],
            [['cause'], 'string', 'max' => 255],
            [['model_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status_number' => Yii::t('app', '状态码'),
            'cause' => Yii::t('app', '原因'),
            'created_at' => Yii::t('app', '生成时间'),
            'model_name' => Yii::t('app', '对应模型：1 Shop,2 ShopManager'),
            'status_type' => Yii::t('app', '状态类型：1审核，2黑名单'),
        ];
    }

    /**
     * @inheritdoc
     * @return ShopStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopStatusQuery(get_called_class());
    }
}
