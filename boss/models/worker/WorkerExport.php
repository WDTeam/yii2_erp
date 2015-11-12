<?php

namespace boss\models\worker;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "{{%worker_ext}}".
 *
 * @property integer $worker_id
 * @property integer $worker_age
 * @property integer $worker_sex
 * @property string $worker_edu
 * @property integer $worker_is_health
 * @property integer $worker_is_insurance
 * @property integer $worker_height
 * @property string $worker_source
 * @property string $worker_bank_name
 * @property string $worker_bank_from
 * @property string $worker_bank_area
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
class WorkerExport extends Model
{
    public $excel;
    public $excel_vacation;

    public function rules(){
        return [
            ['excel','required'],
            ['excel_vacation','required'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'excel' => Yii::t('app', '阿姨信息表：'),
            'excel_vacation' => Yii::t('app', '阿姨请假信息表：'),
        ];
    }
}
