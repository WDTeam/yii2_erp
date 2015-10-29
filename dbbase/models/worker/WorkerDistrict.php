<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_district}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $operation_shop_district_id
 * @property integer $created_ad
 */
class WorkerDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'operation_shop_district_id', 'created_ad'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '自增id'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'operation_shop_district_id' => Yii::t('app', '商圈id'),
            'created_ad' => Yii::t('app', '阿姨录入时间'),
        ];
    }
}
