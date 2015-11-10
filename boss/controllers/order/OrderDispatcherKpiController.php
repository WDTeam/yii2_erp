<?php

namespace boss\controllers\order;

use Yii;
use core\models\order\OrderDispatcherKpi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderDispatcherKpiController implements the CRUD actions for OrderDispatcherKpi model.
 */
class OrderDispatcherKpiController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrderDispatcherKpi models.
     * @return mixed
     */
    public function actionIndex(){

        $kpiModel = new OrderDispatcherKpi();
        $model=null;
        $flag=Yii::$app->request->get("flag");
        if($flag!=2){//��һ�������
            $model = $kpiModel->queryHistoricalKpi(yii::$app->user->id,strtotime(date('y-m-d')));
        }else{//ajax������������
           $attrs=[

                "id"=>Yii::$app->request->get("id"),
                "system_user_id"=>yii::$app->user->id,
               'system_user_name' => yii::$app->user->identity->username,
                "dispatcher_kpi_date"=> strtotime(date('y-m-d')),
               "dispatcher_kpi_status"=>Yii::$app->request->get("dispatcher_kpi_status"),
               "dispatcher_kpi_free_time"=>Yii::$app->request->get("dispatcher_kpi_free_time"),
               "dispatcher_kpi_busy_time"=>Yii::$app->request->get("dispatcher_kpi_busy_time"),
                "dispatcher_kpi_rest_time"=>Yii::$app->request->get("dispatcher_kpi_rest_time"),
               "dispatcher_kpi_end_at"=> strtotime(date('y-m-d')),
                "dispatcher_kpi_obtain_count"=>Yii::$app->request->get("dispatcher_kpi_obtain_count"),
               "dispatcher_kpi_assigned_count"=>Yii::$app->request->get("dispatcher_kpi_assigned_count"),
               "dispatcher_kpi_assigned_rate"=>Yii::$app->request->get("dispatcher_kpi_assigned_rate"),
            ];
            $idd= $kpiModel->updateDisatcherKpi($attrs);
            return $idd;
        }
        return $this->render('index', [
            'model' => $model
        ]);
    }

}
