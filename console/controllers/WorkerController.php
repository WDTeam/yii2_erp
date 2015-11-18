<?php
namespace console\controllers;

use core\models\operation\OperationShopDistrict;
use core\models\worker\Worker;
use core\models\worker\WorkerDistrict;
use core\models\worker\WorkerForRedis;

use Yii;
use yii\console\Controller;
use core\components\ConsoleHelper;


class WorkerController extends Controller{


    /**
     * 初始化Redis中所有阿姨 及 商圈阿姨关联信息
     */
    public function actionInitAllWorkerForRedis()
    {
        WorkerForRedis::initAllWorkerToRedis();
        ConsoleHelper::log('init all worker in Redis success~');
    }

    /**
     *
     */
    public function actionInsertDistrictWorker(){
        $condition = 'isdel=0 and worker_work_city!=110100 and worker_work_city!=310100';
        $result = Worker::find()->select('id,worker_work_city')->where($condition)->asArray()->all();
        $batchWorkerDistrict = [];
        foreach ($result as $item) {
            if($item['worker_work_city']) {
                $district_list = OperationShopDistrict::getCityShopDistrictListByNameAndCityId($item['worker_work_city'], null);
                foreach ($district_list as $d_item) {
                    $batchWorkerDistrict[] = [
                        'worker_id' => $item['id'],
                        'operation_shop_district_id' => $d_item['id'],
                        'create_ad' => time(),
                    ];
                }
            }
        }
        if($batchWorkerDistrict){
            $connectionNew =  \Yii::$app->db;
            $connectionNew->createCommand()->batchInsert('{{%worker_district}}',['worker_id','operation_shop_district_id','created_ad'], $batchWorkerDistrict)->execute();
            echo 'insert worker district success~';
        }
    }
}
