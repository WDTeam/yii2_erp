<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_identity_config}}".
 *
 * @property integer $id
 * @property string $worker_identity_name
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 * @property integer $isdel
 */
class WorkerIdentityConfig extends \dbbase\models\worker\WorkerIdentityConfig
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_identity_config}}';
    }

    /*
     * 获取阿姨所有身份
     * @return array ['id'=>'worker_identity_name',..]
     */
    public static function getWorkerIdentityList(){
        $workerIdentityList = self::find()->select('id,worker_identity_name')->where(['isdel'=>0])->asArray()->all();
        return \yii\helpers\ArrayHelper::map($workerIdentityList,'id','worker_identity_name');
    }

    /*
     * 获取阿姨身份名称
     * @param int worker_identity
     * @return String worker_identity_name
     */
    public static function getWorkerIdentityShow($workerIdentityId){

        $workerIdentityInfo = self::find()->where(['id'=>$workerIdentityId,'isdel'=>0])->asArray()->one();

        return $workerIdentityInfo['worker_identity_name'];
    }
}
