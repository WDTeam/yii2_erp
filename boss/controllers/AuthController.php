<?php

namespace boss\controllers;

use Yii;
use boss\models\Auth;
use boss\models\search\AuthSearch;
use boss\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthController implements the CRUD actions for Auth model.
 */
class AuthController extends Controller
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
     * Lists all Auth models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthSearch();
        $searchModel->type = Auth::TYPE_PERMISSION;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Auth model.
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
     * Creates a new Auth model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Auth();
        $model->type = Auth::TYPE_PERMISSION;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Auth model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Auth model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Auth model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Auth the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auth::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 获取所有操作项目
     */
    public function getAllPermissions()
    {
        $handle = \opendir("../controllers/");
        $permissions = [];
        if ($handle) {
            while (false !== ($fileName = readdir($handle))) {
                if ($fileName != "." && $fileName != "..") {
                    if(preg_match('/(.*?)Controller/i', $fileName, $matches)) {
                        $controller_id = $matches[1];
                        $content = file_get_contents("../controllers/".$fileName);
                        if(preg_match_all('/action(.*?)\(/i', $content, $matches)) {
                            foreach ($matches[1] as $action_id){
                                if(!empty($action_id)){
                                    $permissions[] = $controller_id.$action_id;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $permissions;
    }
    /**
     * 自动生成权限
     */
//     public function actionAutoCreate()
//     {
//         $auth = Yii::$app->authManager;
//         $permissions = $this->getAllPermissions();
//         foreach ($permissions as $controller_id=>$permission){
//             $is_has = $auth->getPermission($permission);
//             if(!$is_has){
//                 $createPost = $auth->createPermission($permission);
//                 $createPost->description = $permission;
//                 $auth->add($createPost);
//             }
//         }
//         //         $auth->assign($createPost, 1);
//     }
}
