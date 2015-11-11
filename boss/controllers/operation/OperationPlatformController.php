<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\models\operation\OperationPlatform;
use boss\models\operation\OperationPlatformVersion;
use boss\models\operation\OperationAdvertContent;
use boss\models\operation\OperationAdvertPosition;
use boss\models\operation\OperationAdvertRelease;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationPlatformController implements the CRUD actions for OperationPlatform model.
 */
class OperationPlatformController extends BaseAuthController
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
     * Lists all OperationPlatform models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationPlatform::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationPlatform model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OperationPlatform model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationPlatform();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationPlatform model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $model->updated_at = time();
            $model->save();

            //更新冗余的平台名称,平台名称冗余在三个表:版本,内容,位置
            OperationPlatformVersion::updatePlatformName($id, $post['OperationPlatform']['operation_platform_name']);
            OperationAdvertContent::updatePlatformName($id, $post['OperationPlatform']['operation_platform_name']);
            OperationAdvertPosition::updatePlatformName($id, $post['OperationPlatform']['operation_platform_name']);


            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OperationPlatform model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        //联动删除
        OperationPlatformVersion::updatePlatformStatus($id);
        OperationAdvertPosition::updateAdvertPositionStatus($id);

        //获取所有的内容编号，根据内容编号删除已发布的广告
        $result = OperationAdvertContent::getAdvertContent($id);
        if ($result != false) {
            foreach ($result as $key => $value) {
                OperationAdvertRelease::updateAdvertReleaseStatus($value['id']);
            }
        }
        OperationAdvertContent::updateAdvertContentStatus($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the OperationPlatform model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationPlatform the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationPlatform::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
