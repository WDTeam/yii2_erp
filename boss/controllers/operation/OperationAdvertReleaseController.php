<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\models\operation\OperationAdvertRelease;
use boss\models\operation\OperationAdvertPosition;
use boss\models\operation\OperationAdvertContent;
use boss\models\operation\OperationCity;
use boss\models\operation\OperationPlatform;
use boss\models\operation\OperationPlatformVersion;
use boss\models\operation\OperationAdvertReleaseSearch;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            'query' => OperationAdvertRelease::find()->groupBy(['city_name']),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationAdvertRelease model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($city_id = '')
    {
        $param = Yii::$app->request->getQueryParams();
        if (isset($city_id) && $city_id != '') {
            $param['OperationAdvertReleaseSearch']['city_id'] = $city_id;
        }

        $searchModel = new OperationAdvertReleaseSearch;
        $dataProvider = $searchModel->search($param);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'city_id' => $city_id,
        ]);
    }
    
    /**
     * 发布广告第一步:选择城市
     */
    public function actionStepFirst()
    {
        $post = Yii::$app->request->post();
        if ($post){
            if (!empty($post['city_ids'])) {
                $citys = OperationCity::find()->asArray()->where(['city_id' => $post['city_ids']])->all();
                $cache = Yii::$app->cache;
                $cache->set('__CITY_INFO__', $citys, 6000);
                return $this->redirect(['step-second']);
            } else {
                return $this->redirect(['step-first']);
            }
        } else {

            $citys = OperationCity::find()->where(['operation_city_is_online' => 1])->all();

            //$c = ['选择要发布的城市'];
            $c = [];
            foreach($citys as $v){$c[$v->city_id] = $v->city_name;}
            return $this->render('step-first', ['citys' => $c]);
        }
    }
    
    /**
     * 发布广告第二步:选择平台
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
     * 发布广告第三步:选择平台版本
     */
    public function actionStepThird()
    {
        $post = Yii::$app->request->post();
        if ($post){
            if (!empty($post['version_id'])) {
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
     * 发布广告第四步:选择可用的广告发布
     */
    public function actionStepForth()
    {
        $post = Yii::$app->request->post();
        if ($post) {

            //检测是否选择了要发布的广告
            $mark = 0;
            foreach ($post['advert'] as $k => $v) {
                if (isset($v['id']) && $v['id'] > 0) {
                    $mark += 1;
                } else {
                    $mark += 0;
                }
            }
            if (!empty($post['advert']) && $mark > 0) {

                //城市数据
                $cache = Yii::$app->cache;
                $citys = $cache->get('__CITY_INFO__');
                $model = new OperationAdvertRelease();
                foreach ($citys as $k => $city) {
                    foreach ($post['advert'] as $key => $val) {
                        if (!isset($val['id']) || $val['id'] == '') {
                            continue;
                        }

                        //广告数据
                        $data = [
                            '_csrf' => $post['_csrf'],
                            'OperationAdvertRelease' => [
                                'city_id'   => $city['city_id'],
                                'city_name' => $city['city_name'],
                                'advert_content_id' => $val['id'],
                                'starttime' => $val['starttime'] ? $val['starttime'] : '',
                                'endtime'   => $val['endtime'] ? $val['endtime'] : '',
                                'created_at' => time(),
                                'updated_at' => time()
                            ]
                        ];

                        $_model = clone $model;
                        $_model->load($data);
                        $_model->save();
                    }
                }
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['step-forth']);
            }
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
            return $this->render('step-forth', ['data' => $data]);
        }
    }

    /**
     * Updates an existing OperationAdvertRelease model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $city_id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $result = OperationAdvertRelease::updateReleaseAdvInfo($id, $city_id, $post['OperationAdvertRelease']);
            if ($result['code'] == 200) {
                return $this->redirect(['view', 'city_id' => $city_id]);
            } else {
                \Yii::$app->getSession()->setFlash('default', $result['errmsg']);
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

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
     * 保存同一个城市里广告的顺序
     */
    public function actionSaveOrders(){
        $data = Yii::$app->request->post();

        $model = new OperationAdvertRelease();
        $result = $model->saveReleaseAdvOrder($data);

        //print_r($resutl);
        //if ($result > 0) {
            //return '有' . $result . '次保存成功!';
        //} else {
            //return '排序没有变化!';
        //}
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
