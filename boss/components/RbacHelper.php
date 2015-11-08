<?php
/**
 * @author CoLee
 */
namespace boss\components;

use yii\base\Component;
use yii\helpers\ArrayHelper;
class RbacHelper extends Component
{
    const CACHE_NAME = 'boss-menus';
    public static $permissions = [];
    /**
     * 处理菜单
     * @param array $menus
     */
    public static function menu($menus)
    {
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        self::$permissions = ArrayHelper::map($permissions, 'name', 'description');
        
        $menus = self::recursiveInitMenu($menus);
        \Yii::$app->cache->set(self::CACHE_NAME, $menus, 3600*24);
        return $menus;
    }
    /**
     * 递归初始化菜单
     */
    private static function recursiveInitMenu($menus)
    {
        $auth = \Yii::$app->authManager;
        foreach ($menus as $key=>$menu){
            $url = $menu['url'];
            if(isset($menu['can'])){
                $name = $menu['can'];
            }elseif (isset($url[0]) && $url[0]!='#'){
                $name = $url[0];
            }
            if(!empty($name)){
                $permission = $auth->getPermission($name);
                if(empty($permission)){
                    $permission = $auth->createPermission($name);
                    $permission->description = $menu['label'];
                    $auth->add($permission);
                    $admin = $auth->getRole('super_admin');
                    $auth->addChild($admin, $permission);
                }
                $menu['visible'] = \Yii::$app->user->can($name);
                unset(self::$permissions[$name]);
            }
            if(isset($menu['items'])){
                $menu['items'] = self::recursiveInitMenu($menu['items']);
            }
            $menus[$key] = $menu;
        }
        return $menus;
    }
    
    
    /**
     * 
     */
    public static function getMenus()
    {
        $menus = \Yii::$app->cache->get(self::CACHE_NAME);
        return $menus;
    }
}