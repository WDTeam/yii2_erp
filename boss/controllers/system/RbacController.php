<?php
namespace boss\controllers\general;

use Yii;
use core\models\system\SystemUser;
use yii\rbac\Item;
use core\models\auth\AuthItem;
use core\models\auth\AuthItemSearch;
use boss\components\BaseAuthController;
class RbacController extends BaseAuthController
{
    public function actionCreate()
    {
        if (isset($_POST['AuthItem'])) {
            $model = new Item();
            $item = $_POST['AuthItem'];
            $auth = Yii::$app->authManager;
            $model->name = $item['name'];
            $model->type = $item['type'];
            $model->description = $item['description'];
            $model->data = $item['data'];
            $auth->add($model);
            return $this->redirect(['index']);
        } 
        return $this->render('create', [
            'model' => new AuthItem(),
        ]);
        
    }
    public function actionCreatePermission()
    {
        $actions = explode(",", $_POST['actions']);
        $auth = Yii::$app->authManager;
        foreach ($actions as $name){
            $permission = $auth->createPermission($name);
            $auth->add($permission);
        }
        echo json_encode(['code'=>200,'data'=>"succeed!"]);
    }
    /**
     * Gets the controllers and the modules' controllers for the autocreating of
     * authItems
     */
    public function actionAuto() {
        $controllers = $this->_getControllers();
         return $this->render('auto', [
            'controllers' => $controllers,
        ]);
    }
    
    /**
     * Geting all the application's and  modules controllers
     * @return array The application's and modules controllers
     */
    private function _getControllers() {
        $contPath = Yii::$app->getControllerPath();
        $controllers = $this->_scanDir($contPath,'app-boss');
        //Scan modules
        $modules = Yii::$app->getModules();
        unset($modules['utility']);
        unset($modules['debug']);
        unset($modules['gii']);
        $modControllers = array();
        foreach ($modules as $mod_id => $mod) {
            $moduleControllersPath = Yii::$app->getModule($mod_id)->controllerPath;
            $modControllers = $this->_scanDir($moduleControllersPath, $mod_id, "", $modControllers);
        }
        return array_merge($controllers, $modControllers);
    }
    
    private function _scanDir($contPath, $module="", $subdir="", $controllers = array()) {
        $handle = opendir($contPath);
        while (($file = readdir($handle)) !== false) {
            $filePath = $contPath . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath)) {
                if (preg_match("/^(.+)Controller.php$/", basename($file))) {
                    $controllername = ( ($subdir) ? $subdir . "." : "") . str_replace (".php", "", $file);
                    $controller_id = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', str_replace("Controller", "", $controllername)));
                    if($module=='app-boss'){
                        $classname = Yii::$app->controllerNamespace."\\".$controllername;
                    }else{
                        $classname = Yii::$app->getModule($module)->controllerNamespace."\\".$controllername;
                    }
                    $cont = new $classname($controller_id,Yii::$app->getModule($module));
                    if($cont instanceof BaseAuthController){
                        $controllers[$module][] = $controllername;
                    }
                }
            } else if (is_dir($filePath) && $file != "." && $file != "..") {
                $controllers = $this->_scanDir($filePath, $module, $file, $controllers);
            }
        }
        return $controllers;
    }
    
    public function actionSelectAuthItem(){
        $controller = explode("@@", $_POST['controller']);
        if(count($controller)==2){
            $module_id = $controller[0];
            $controller_name = $controller[1];
            $controller_id = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', str_replace("Controller", "", $controller_name)));
            if($module_id=='app-boss'){
                $file_path = Yii::$app->getControllerPath().'/'.$controller_name.'.php';
            }else{
                $file_path = Yii::$app->getModule($module_id)->controllerPath.'/'.$controller_name.'.php';                
            }
            $h = file($file_path);
            $actions = [];
            for ($i = 0; $i < count($h); $i++) {
                $line = trim($h[$i]);
                if (preg_match("/^(.+)function( +)action*/", $line)) {
                    $posAct = strpos(trim($line), "action");
                    $posPar = strpos(trim($line), "(");
                    $action = trim(substr(trim($line),$posAct, $posPar-$posAct));
                    $patterns[0] = '/\s*/m';
                    $patterns[1] = '#\((.*)\)#';
                    $patterns[2] = '/\{/m';
                    $replacements[2] = '';
                    $replacements[1] = '';
                    $replacements[0] = '';
                    $action = preg_replace($patterns, $replacements, trim($action));
                    $action_id = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', str_replace("action", "", $action)));
                    $itemId = $module_id ."@@" . $controller_id ."@".$action_id;
                    if ($action != "actions") {
                        $auth = Yii::$app->authManager;
                        if ($auth->getPermission($itemId) === null) {
                                $actions[] = $itemId;
                        }
                    } 
                }
            }
            echo json_encode(['code'=>200,'data'=>['actions'=>$actions]]);
        }
    }
    public function actionDeleteAuthItem(){
        $controller = explode("@@", $_POST['controller']);
        if(count($controller)==2){
            $module_id = $controller[0];
            $controller_name = $controller[1];
            $controller_id = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', str_replace("Controller", "", $controller_name)));
            if($module_id=='app-boss'){
                $file_path = Yii::$app->getControllerPath().'/'.$controller_name.'.php';
            }else{
                $file_path = Yii::$app->getModule($module_id)->controllerPath.'/'.$controller_name.'.php';                
            }
            $h = file($file_path);
            $actions = [];
            $auth = Yii::$app->authManager;
            for ($i = 0; $i < count($h); $i++) {
                $line = trim($h[$i]);
                if (preg_match("/^(.+)function( +)action*/", $line)) {
                    $posAct = strpos(trim($line), "action");
                    $posPar = strpos(trim($line), "(");
                    $action = trim(substr(trim($line),$posAct, $posPar-$posAct));
                    $patterns[0] = '/\s*/m';
                    $patterns[1] = '#\((.*)\)#';
                    $patterns[2] = '/\{/m';
                    $replacements[2] = '';
                    $replacements[1] = '';
                    $replacements[0] = '';
                    $action = preg_replace($patterns, $replacements, trim($action));
                    $action_id = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', str_replace("action", "", $action)));
                    $itemId = $module_id ."@@" . $controller_id ."@".$action_id;
                    if ($action != "actions") {
                        if ($auth->getPermission($itemId) != null) {
                                $actions[] = $itemId;
                        }
                    } 
                }
            }
            foreach ($actions as $name){
                $permission = $auth->getPermission($name);
                $auth->remove($permission);
            }
            echo json_encode(['code'=>200,'data'=>'该控制器中的授权项已删除！']);
        }
    }
    public function actionUpdate($id)
    {
        if (isset($_POST['AuthItem'])) {
            $model = new Item();
            $item = $_POST['AuthItem'];
            $auth = Yii::$app->authManager;
            $model->name = $item['name'];
            $model->type = $item['type'];
            $model->description = $item['description'];
            $model->data = $item['data'];
            $auth->update($id,$model);
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => AuthItem::findOne(["name"=>$id]),
        ]);
    }
    public function actionDelete($id){
            $auth = Yii::$app->authManager;
            $model = new Item();
            $model->name = $id;
            $auth->remove($model);
            return $this->redirect(['index']);
    }
    public function actionIndex(){
        $searchModel = new AuthItemSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    public function actionPermissionPermission()
    {
        $parent = $_POST['permission'];
        $auth = Yii::$app->authManager;
        $authParent = $auth->getPermission($parent);
        if(isset($_POST['permissions'])){
            if(!empty($_POST['permissions']['assign'])){
                $permissions['assign'] = explode(",", $_POST['permissions']['assign']);
                foreach($permissions['assign'] as $permission){
                    $authPermission = $auth->getPermission($permission);
                    $auth->addChild($authParent, $authPermission);
                }
            }
            if(!empty($_POST['permissions']['revoke'])){
                $permissions['revoke'] = explode(",", $_POST['permissions']['revoke']);
                foreach($permissions['revoke'] as $permission){
                    $authPermission = $auth->getPermission($permission);
                    $auth->removeChild($authParent, $authPermission);
                }
            }
        }
        $parent_permissions = $auth->getChildren($parent);
        $permissions = $auth->getPermissions();
        echo json_encode(['code'=>200,'data'=>['a'=>array_diff_key($permissions,$parent_permissions,[$parent=>$auth->getPermission($parent)]),'b'=>$parent_permissions]]);
        
        
    }
    public function actionRolePermission()
    {
        $role = $_POST['role'];
        $auth = Yii::$app->authManager;
        $authRole = $auth->getRole($role);
        if(isset($_POST['permissions'])){
            if(!empty($_POST['permissions']['assign'])){
                $permissions['assign'] = explode(",", $_POST['permissions']['assign']);
                foreach($permissions['assign'] as $permission){
                    $authPermission = $auth->getPermission($permission);
                    $auth->addChild($authRole, $authPermission);
                }
            }
            if(!empty($_POST['permissions']['revoke'])){
                $permissions['revoke'] = explode(",", $_POST['permissions']['revoke']);
                foreach($permissions['revoke'] as $permission){
                    $authPermission = $auth->getPermission($permission);
                    $auth->removeChild($authRole, $authPermission);
                }
            }
        }
        $role_permissions = $auth->getPermissionsByRole($role);
        $permissions = $auth->getPermissions();
        echo json_encode(['code'=>200,'data'=>['a'=>array_diff_key($permissions,$role_permissions),'b'=>$role_permissions]]);
        
        
    }
    public function actionUserRole()
    {
        $user = $_POST['user'];        
        $auth = Yii::$app->authManager;
        if(isset($_POST['roles'])){
            if(!empty($_POST['roles']['assign'])){
                $roles['assign'] = explode(",", $_POST['roles']['assign']);        
                foreach($roles['assign'] as $role){
                    $authRole = $auth->getRole($role);
                    $auth->assign($authRole, $user);
                }
            }
            if(!empty($_POST['roles']['revoke'])){
                $roles['revoke'] = explode(",", $_POST['roles']['revoke']);        
                foreach($roles['revoke'] as $role){
                    $authRole = $auth->getRole($role);
                    $auth->revoke($authRole, $user);
                }
            }
        }
        $user_roles = $auth->getRolesByUser($user);
        $roles = $auth->getRoles();
        echo json_encode(['code'=>200,'data'=>['a'=>array_diff_key($roles,$user_roles),'b'=>$user_roles]]);
    }
    public function actionAssign(){
        $auth = Yii::$app->authManager;
        $data['permissions'] = $auth->getPermissions();
        $data['roles'] = $auth->getRoles();
        $data['users'] = SystemUser::find()->orderBy('username')->all();
        return $this->render('assign', [
            'data' => $data,
        ]);
    }
}