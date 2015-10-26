<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationAdvertRelease;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use boss\models\Operation\OperationPlatform;
//use boss\models\Operation\OperationPlatformVersion;
use boss\models\Operation\OperationAdvertPosition;
use boss\models\Operation\OperationAdvertContent;
use boss\models\Operation\OperationCity;
use boss\models\Operation\OperationPlatform;
use boss\models\Operation\OperationPlatformVersion;
//use yii\caching\Cache;

/**
 * OperationAdvertReleaseController implements the CRUD actions for OperationAdvertRelease model.
 */
class OperationAdvertReleaseController extends BaseAuthController
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
     * Lists all OperationAdvertRelease models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationAdvertRelease::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionTest(){
        $data = OperationAdvertRelease::getAdvertList(110100, 5, 6, 6);
        var_dump($data);
    }

    /**
     * Displays a single OperationAdvertRelease model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $adverts = unserialize($model->operation_release_contents);
        $advertinfos = OperationAdvertContent::find()->asArray()->where(['id' => $adverts['id']])->all();
        $i = 0;
        foreach($advertinfos as $key => $value){
            $advertinfos[$key]['starttime'] = $adverts['starttime'][$i];
            $advertinfos[$key]['endtime'] = $adverts['endtime'][$i];
            $i++;
        }
//        $model->operation_release_contents = serialize($adverts);
        return $this->render('view', [
            'model' => $model,
            'adverts' => $advertinfos,
        ]);
    }
    
    /**
     * 
     */
    public function actionStepFirst()
    {
        $post = Yii::$app->request->post();
        if ($post){
            $citys = OperationCity::find()->asArray()->where(['city_id' => $post['city_ids']])->all();
            $cache = Yii::$app->cache;
            $cache->set('__CITY_INFO__', $citys, 6000);
            return $this->redirect(['step-second']);
        } else {
            
            $citys = OperationCity::find()->all();
//            $c = ['选择要发布的城市'];
            $c = [];
            foreach($citys as $v){$c[$v->city_id] = $v->city_name;}
            return $this->render('step-first', ['citys' => $c]);
        }
    }
    
    /**
     * 
     */
    public function actionStepSecond()
    {
        $post = Yii::$app->request->post();
        if ($post){
            if(!empty($post['platform_id'])){
                $platform_ids = $post['platform_id'];
                $platforms = OperationPlatform::find()->asArray()->where(['id' => $platform_ids])->all();
                $cache = Yii::$app->cache;
                $cache->set('__PLATFORMS_INFO__', $platforms, 6000);
                return $this->redirect(['step-third', 'platform_ids' => $platform_ids]);
            }else{
                return $this->redirect(['step-second']);
            }
        } else {
            $platforms = OperationPlatform::find()->asArray()->all();
            $data = [];
            foreach($platforms as $key => $value){
                $data[$value['id']] = $value['operation_platform_name'];
            }
            return $this->render('step-second', ['platforms' => $data]);
        }
    }
    
    /**
     * 
     */
    public function actionStepThird()
    {
        $post = Yii::$app->request->post();
        if ($post){
            $version_ids = $post['version_id'];
            $cache = Yii::$app->cache;
            $cache->set('__VERSIONS_INFO__', $version_ids, 6000);
            return $this->redirect(['step-forth']);
        } else {
            $platform_ids = Yii::$app->request->get('platform_ids');
            $platforms = OperationPlatform::find()->asArray()->where(['id' => $platform_ids])->all();
            foreach($platforms as $k => $v){
                $versions = OperationPlatformVersion::find()->asArray()->where(['operation_platform_id' => $v['id']])->all();
                $platforms[$k]['versions'] = $versions;
            }
            return $this->render('step-third', ['platforms' => $platforms]);
        }
    }
    
    /**
     * 
     */
    public function actionStepForth()
    {
        $post = Yii::$app->request->post();
        if ($post){
            $cache = Yii::$app->cache;
            $citys = $cache->get('__CITY_INFO__');
            $model = new OperationAdvertRelease();
            foreach($citys as $k => $city){
                $data = [
                    '_csrf' => $post['_csrf'],
                    'OperationAdvertRelease' => [
                        'city_id' => $city['city_id'],
                        'city_name' => $city['city_name'],
                        'operation_release_contents' => serialize($post['advert']),
                        'created_at' => time(),
                        'updated_at' => time()
                    ]
                ];
                $_model = clone $model;
                $_model->load($data);
                $_model->save();
            }
            return $this->redirect(['index']);
        } else {
            $cache = Yii::$app->cache;
            $platforms = $cache->get('__PLATFORMS_INFO__');
            $versions = $cache->get('__VERSIONS_INFO__');
            $data = [];
            //读取所有符合条件的广告内容
            foreach($platforms as $key =>$platform){
                if(!empty($versions[$platform['id']])){
                    //如果有平台和版本则：
                    foreach($versions[$platform['id']] as $k => $v){
                        $where = ['AND',['platform_id' => $platform['id']],['platform_version_id' => $v]];
                        $adverts = OperationAdvertContent::find()->asArray()->where($where)->all();
                        foreach($adverts as $advert){
                            array_push($data, $advert);
                        }
                    }
                }else{
                    //如果有平台则：
                    $adverts = OperationAdvertContent::find()->asArray()->where(['platform_id' => $platform['id']])->all();
                    foreach($adverts as $advert){
                        array_push($data, $advert);
                    }
                }
            }
//            var_dump($data);exit;
            return $this->render('step-forth', ['data' => $data]);
        }
    }

    /**
     * Creates a new OperationAdvertRelease model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new OperationAdvertRelease();
//        $post = Yii::$app->request->post();
//        if ($model->load($post)){
//            $model->save();
//            return $this->redirect(['index']);
//        } else {
//            $citys = OperationCity::find()->all();
//            $c = ['选择要发布的城市'];
//            foreach($citys as $v){$c[$v->city_id] = $v->city_name;}
//            return $this->render('create', [
//                'model' => $model,
//                'citys' => $c
//            ]);
//        }
//    }

    /**
     * Updates an existing OperationAdvertRelease model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//        $post = Yii::$app->request->post();
//        if ($model->load($post)) {
//            $_model = clone $model;
//            $position_id = $post['OperationAdvertRelease']['operation_advert_position_id'];
//            $position = OperationAdvertPosition::find()->where(['id' => $position_id])->one();
//            $post['OperationAdvertRelease']['operation_advert_position_name'] = $position->operation_advert_position_name;
//            $post['OperationAdvertRelease']['operation_advert_contents'] = serialize($post['OperationAdvertRelease']['operation_advert_contents']);
//            $_model->operation_platform_id = $position->operation_platform_id;
//            $_model->operation_platform_name = $position->operation_platform_name;
//            $_model->operation_platform_version_id = $position->operation_platform_version_id;
//            $_model->operation_platform_version_name = $position->operation_platform_version_name;
//            $_model->load($post);
//            $_model->save();
//            return $this->redirect(['index']);
//        } else {
//            $position = OperationAdvertPosition::find()->all();
//            $positions = ['选择广告位置'];
//            foreach($position as $v){$positions[$v->id] = $v->operation_advert_position_name;}
//            $content = OperationAdvertContent::find()->all();
//            foreach($content as $k => $v){
//                $contents[$v->id] = $v->operation_advert_position_name.'('.$v->operation_city_name.':'.$v->operation_platform_name.$v->operation_platform_version_name.')';
//            }
//            return $this->render('update', [
//                'model' => $model,
//                'positions' => $positions,
//                'contents' => $contents,
//            ]);
//        }
//    }

    /**
     * Deletes an existing OperationAdvertRelease model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OperationAdvertRelease model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationAdvertRelease the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationAdvertRelease::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
