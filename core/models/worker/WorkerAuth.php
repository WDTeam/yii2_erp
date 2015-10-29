<?php

namespace core\models\worker;

use Yii;
use yii\web\BadRequestHttpException;
USE yii\web\NotFoundHttpException;

/**
 * This is the model class for table "ejj_worker_auth".
 *
 * @property integer $worker_id
 * @property integer $worker_auth_status
 * @property string $worker_auth_failed_reason
 * @property integer $worker_basic_training_status
 * @property integer $worker_ontrial_status
 * @property string $worker_ontrial_failed_reason
 * @property integer $worker_onboard_status
 * @property string $worker_onboard_failed_reason
 * @property integer $worker_rising_training_status
 */
class WorkerAuth extends \dbbase\models\worker\WorkerAuth
{
    /**
     * 获取阿姨审核Model
     * @param $worker_id
     * @return null|static
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public static function findModel($worker_id){
        if(empty($worker_id)){
            throw new BadRequestHttpException('worker_id not found.');
        }
        $workerAuthModel = self::findOne($worker_id);

        if($workerAuthModel!==null){
            return $workerAuthModel;
        }else{
            throw new NotFoundHttpException('The workerAuth info not exist.');

        }
    }
}
