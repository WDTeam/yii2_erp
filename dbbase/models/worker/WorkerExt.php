<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_ext}}".
 *
 * @property integer $worker_id
 * @property integer $worker_age
 * @property integer $worker_sex
 * @property integer $worker_birth
 * @property string $worker_edu
 * @property string $worker_hometown
 * @property integer $worker_is_health
 * @property integer $worker_is_insurance
 * @property string $worker_source
 * @property string $worker_bank_name
 * @property string $worker_bank_from
 * @property string $worker_bank_card
 * @property integer $worker_live_province
 * @property integer $worker_live_city
 * @property integer $worker_live_area
 * @property string $worker_live_street
 * @property double $worker_live_lng
 * @property double $worker_live_lat
 * @property integer $created_ad
 * @property integer $updated_ad
 */
class WorkerExt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_ext}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_age', 'worker_sex', 'worker_is_health', 'worker_is_insurance', 'worker_live_province', 'worker_live_city', 'worker_live_area', 'created_ad', 'updated_ad','worker_height','worker_bank_card'], 'integer'],
            [['worker_live_lng', 'worker_live_lat'], 'number'],
            [['worker_age','worker_sex','worker_source','worker_bank_name','worker_bank_from','worker_bank_area','worker_bank_card','worker_live_province', 'worker_live_city', 'worker_live_area'],'required'],
            [['worker_edu'], 'string', 'max' => 30],
            ['worker_bank_card', 'match','pattern'=>'/^[0-9]{16}|(0-9){19}$/','message'=>'银行卡号必须16位或19位数字'],
            [['worker_live_street'], 'string', 'max' => 50],
            [['worker_bank_name'], 'string', 'max' => 10],
            [['worker_bank_from','worker_bank_area'], 'string', 'max' => 50],
            ['worker_height','number','min'=>100,'max'=>200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'worker_id' => Yii::t('app', '阿姨id'),
            'worker_age' => Yii::t('app', '阿姨年龄'),
            'worker_sex' => Yii::t('app', '阿姨性别'),
            'worker_height' => Yii::t('app', '阿姨身高(cm)'),
            'worker_edu' => Yii::t('app', '阿姨教育程度'),
            'worker_is_health' => Yii::t('app', '阿姨是否有健康证'),
            'worker_is_insurance' => Yii::t('app', '阿姨是否上保险'),
            'worker_source' => Yii::t('app', '阿姨来源'),
            'worker_bank_name' => Yii::t('app', '开户银行'),
            'worker_bank_from' => Yii::t('app', '银行卡开户网点'),
            'worker_bank_area' => Yii::t('app', '银行卡开户地'),
            'worker_bank_card' => Yii::t('app', '银行卡号'),
            'worker_live_province' => Yii::t('app', '阿姨居住地(省份)'),
            'worker_live_city' => Yii::t('app', '阿姨居住地(市)'),
            'worker_live_area' => Yii::t('app', '阿姨居住地(区,县)'),
            'worker_live_street' => Yii::t('app', '阿姨居住地(详细地址)'),
            'worker_live_lng' => Yii::t('app', '阿姨居住地经度'),
            'worker_live_lat' => Yii::t('app', '阿姨居住地纬度'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
        ];
    }
}
