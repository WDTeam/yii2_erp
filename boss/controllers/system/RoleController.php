<?php
/**
 * @author CoLee
 */
namespace boss\controllers\system;

use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;

use core\models\auth\Auth;
use core\models\auth\AuthSearch;
use yii\filters\VerbFilter;
use boss\components\BaseAuthController;
use yii\helpers\ArrayHelper;

class RoleController extends BaseAuthController
{

    public function actionIndex()
    {
        $searchModel = new AuthSearch();
        $searchModel->type = Auth::TYPE_ROLE;
        $dataProvider = $searchModel->search(Yii::$app->request->get(), Auth::TYPE_ROLE);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Auth();
        if ($model->load(Yii::$app->request->post())) {
            $permissions = $this->preparePermissions(Yii::$app->request->post());
            if($model->createRole($permissions)) {
                Yii::$app->session->setFlash('success', " '$model->name' " . Yii::t('app', 'successfully saved'));
                return $this->redirect(['view', 'id' => $model->name]);
            }
            else
            {
                $permissions = $this->getPermissions();
                $model->_permissions = Yii::$app->request->post()['Auth']['_permissions'];
                return $this->render('create', [
                        'model' => $model,
                        'permissions' => $permissions
                    ]
                );
            }
        } else {
            $permissions = $this->getPermissions();
            return $this->render('create', [
                    'model' => $model,
                    'permissions' => $permissions
                ]
            );
        }
    }

    public function actionUpdate($id)
    {
        $name = $id;
        $model = $this->findModel($name);
        if ($model->load(Yii::$app->request->post())) {
            $permissions = $this->preparePermissions(Yii::$app->request->post());
            if($model->updateRole($name, $permissions)) {
                Yii::$app->session->setFlash('success', " '$model->name' " . Yii::t('app', 'successfully updated'));
                return $this->redirect(['update', 'id' => $name]);
            }
        } else {
            $permissions = $this->getPermissions();
            $model->loadRolePermissions($name);
            return $this->render('update', [
                    'model' => $model,
                    'permissions' => $permissions,
                ]
            );
        }
    }

    public function actionDelete($id)
    {
        $name = $id;
        if ($name) {
            if(Auth::hasUsersByRole($name)) {
                $auth = Yii::$app->getAuthManager();
                $role = $auth->getRole($name);

                // clear asset permissions
                $permissions = $auth->getPermissionsByRole($name);
                foreach($permissions as $permission) {
                    $auth->removeChild($role, $permission);
                }
                if($auth->remove($role)) {
                    Yii::$app->session->setFlash('success', " '$name' " . Yii::t('app', 'successfully removed'));
                }
            } else {
                Yii::$app->session->setFlash('warning', " '$name' " . Yii::t('app', 'still used'));
            }
        }
        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        $name = $id;
        $model = $this->findModel($name);
        $model->loadRolePermissions($name);
        $permissions = $this->getPermissions();
        return $this->render('view', [
            'model' => $model,
            'permissions' => $permissions,
        ]);
    }

    protected function findModel($name)
    {
        if ($name) {
            $auth = Yii::$app->getAuthManager();
            $model = new Auth();
            $role = $auth->getRole($name);
            if ($role) {
                $model->name = $role->name;
                $model->description = $role->description;
                $model->setIsNewRecord(false);
                return $model;
            }
        }
        throw new HttpException(404);
    }

    protected function getPermissions() {
        $models = Auth::find()->where(['type' => Auth::TYPE_PERMISSION])->all();
        $permissions = [];
        foreach($models as $model) {
            $permissions[$model->name] = Yii::t('auth',$model->description);
        }
        return $permissions;
    }

    protected function preparePermissions($post) {
        return (isset($post['Auth']['_permissions']) &&
            is_array($post['Auth']['_permissions'])) ? $post['Auth']['_permissions'] : [];
    }
}
