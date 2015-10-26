<?php

namespace boss\controllers;

use Yii;
use boss\models\Question;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\models\Courseware;
use boss\models\Category;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends BaseAuthController
{
    public $is_category_manage = true;
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
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex($courseware_id)
    {
        $courseware = Courseware::findOne($courseware_id);
        $category = Category::findOne($courseware->classify_id);
        $dataProvider = new ActiveDataProvider([
            'query' => Question::find()->where(['courseware_id' => $courseware_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'courseware' => $courseware,
            'category' => $category,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
           
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($courseware_id)
    {
        $category_id = \Yii::$app->request->get('category_id',0);
        $model = new Question();
        $model->courseware_id = $courseware_id;
        $model->category_id = $category_id;
        $model->is_multi = 0;
//        echo    $courseware_id; exit;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->correct_options = str_replace('，', ',', $model->correct_options);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $cid = $model->courseware_id;
        $model->delete();
        return $this->redirect(['index', 'courseware_id' => $cid]);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
