<?php

namespace boss\models\worker;

use Yii;

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
class WorkerExt extends \core\models\worker\WorkerExt
{
    public static function getWorkerHeightShow($worker_height){
        if($worker_height){
            return $worker_height.' cm';
        }
    }
    public static function getWorkerSourceShow($workerSource){
        $sourceConfig = ['1'=>'蓝领招聘','2'=>'家政公司','3'=>'直营门店','4'=>'阿姨推荐'];
        return isset($sourceConfig[$workerSource])?$sourceConfig[$workerSource]:null;
    }
}
