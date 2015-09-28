<?php

namespace core\controllers;

use Yii;
use common\models\CustomerTransRecord;
use core\models\CustomerTransRecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerTransRecordController implements the CRUD actions for CustomerTransRecord model.
 */
class CustomerTransRecordController extends Controller
{
    private $success = ['status'=>1,'data'=>'','msg'=>'操作成功'];
    private $errors = ['status'=>0,'data'=>'','msg'=>'操作失败'];
    /**
     * Creates a new CustomerTransRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function create($data)
    {
        $model = new CustomerTransRecord;
        $model->attributes = $data;
        if($model->save() !== null){
            return $this->success = ['status'=>1,'data'=>'操作成功','msg'=>''];
        }
        return $this->errors;

    }


    /**
     * Finds the CustomerTransRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerTransRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerTransRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
