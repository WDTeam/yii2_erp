<?php

namespace boss\controllers;

use Yii;
use boss\models\Courseware;
use boss\models\CoursewareSearch;
use yii\data\ActiveDataProvider;
use boss\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CoursewareController implements the CRUD actions for Courseware model.
 */
class CoursewareController extends Controller
{
    public $is_category_manage = true;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Courseware models.
     * @return mixed
     */
    public function actionIndex($classify_id)
    {
        $category = Category::findOne($classify_id);
        $dataProvider = new ActiveDataProvider([
            'query' => Courseware::find()->where(['classify_id' => $classify_id])->orderby('order_number ASC'),
             'pagination' => [
                    'pagesize' => '10',
             ]
        ]);
//        $searchModel = new CoursewareSearch;
//        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());//->orderby('order_number ASC');
//        $dataProvider->query->andFilterWhere(['classify_id'=>$classify_id]);
//         var_dump($dataProvider->query);exit;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
//            'searchModel' => $searchModel,
            'classify_id'=>$classify_id,
            'category' => $category,
        ]);
    }

    /**
     * Displays a single Courseware model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $classify_id= Yii::$app->request->get('classify_id');
        $category = Category::findOne($classify_id);
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model, 'category'=> $category]);
}
    }

    /**
     * Creates a new Courseware model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($classify_id)
    {
        $classify_id= Yii::$app->request->get('classify_id');
        $category = Category::findOne($classify_id);
        $max = Courseware::findBySql('
            select MAX(order_number) from {{%courseware}}
        ')->scalar();
        $model = new Courseware;
        $model->classify_id = $classify_id;
        $model->order_number = $max+1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'classify_id'=>$classify_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'category'=> $category
            ]);
        }
    }

    /**
     * Updates an existing Courseware model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $classify_id= Yii::$app->request->get('classify_id');
        $category = Category::findOne($classify_id);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'classify_id'=>$model->classify_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'category'=> $category
            ]);
        }
    }

    /**
     * Deletes an existing Courseware model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $classify_id= Yii::$app->request->get('classify_id');
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'classify_id' => $classify_id]);
    }
    
    public function actionSaveorders($id){
        $data = Yii::$app->request->post();
        if($data['direction'] == 'up'){
            $result = $this->toup($id);
        }elseif($data['direction'] == 'down'){
            $result = $this->todown($id);
        }
        exit(json_encode($result));
    }
    
    private function toup($id){
        $m1 = $this->findModel($id);
        $m2 = Courseware::find()->where(['and', ['=', 'classify_id', $m1->classify_id],['<', 'order_number',$m1->order_number]])->orderby('order_number DESC')->one();
        if(empty($m2)){
            return ['result' => false, 'msg' => '已经在最顶部了'];
        }
        $ord = $m1->order_number;
        $m1->order_number = $m2->order_number;
        $m2->order_number = $ord;
        $m1->save(false);
        $m2->save(false);
        return ['result' => true];
    }
    
    private function todown($id){
        $m1 = $this->findModel($id);
        $m2 = Courseware::find()->where(['and', ['=', 'classify_id', $m1->classify_id],['>', 'order_number',$m1->order_number]])->orderby('order_number ASC')->one();
        if(empty($m2)){
            return ['result' => false, 'msg' => '已经在最底部了'];
        }
        $ord = $m1->order_number;
        $m1->order_number = $m2->order_number;
        $m2->order_number = $ord;
        $m1->save(false);
        $m2->save(false);
        return ['result' => true];
    }

    /**
     * Finds the Courseware model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Courseware the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courseware::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
