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
use core\models\auth\AuthItemChild;

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
        $auth = \Yii::$app->authManager;
        $model = new Auth();
        if ($model->load(Yii::$app->request->post())) {
            $role = $auth->createRole($model->name);
            $role->description = $model->description;
            $auth->add($role);
            foreach ($model->permissions as $name)
            {
                $permission = $auth->getPermission($name);
                $auth->addChild($role, $permission);
            }
            return $this->redirect(['index']);
        }
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('create', [
            'model' => $model,
            'permissions' => $permissions
        ]);
    }

    public function actionUpdate($id)
    {
        $auth = \Yii::$app->authManager;
        $model = $this->findModel($id);
        $role = $auth->getRole($id);

        if ($model->load(Yii::$app->request->post())) {
            $role->description = $model->description;
            $auth->update($id, $role);
            
            $perms = (array)$auth->getPermissionsByRole($id);
            foreach ($perms as $perm){
                $auth->removeChild($role, $perm);
            }
            
            foreach ($model->permissions as $name)
            {
                $permission = $auth->getPermission($name);
                $auth->addChild($role, $permission);
            }
            return $this->refresh();
        }
        $model->permissions = AuthItemChild::find()
        ->select(['child'])
        ->where(['parent'=>$id])
        ->column();
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('update', [
            'model' => $model,
            'permissions' => $permissions
        ]);
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
}
