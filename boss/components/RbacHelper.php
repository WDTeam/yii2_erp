<?php
/**
 * @author CoLee
 */
namespace boss\components;

use yii\base\Component;
use yii\helpers\ArrayHelper;
class RbacHelper extends Component
{
    const CACHE_MENU = 'boss-menus';
    const CACHE_TOP_MENU = 'boss-top-menus';
    public static $permissions = [];
    protected static function setPermissions()
    {
        if(empty(self::$permissions)){
            $auth = \Yii::$app->authManager;
            $permissions = $auth->getPermissions();
            self::$permissions = ArrayHelper::map($permissions, 'name', 'description');
        }
    }
    /**
     * 处理左菜单
     * @param array $menus
     */
    public static function menu($menus)
    {
        $_menus = self::getMenus();
        if(!empty($_menus)){
            return $_menus;
        }
        self::setPermissions();
        $menus = self::recursiveInitMenu($menus);
        \Yii::$app->session->set(self::CACHE_MENU, $menus);
        return $menus;
    }
    /**
     * 处理顶部菜单
     * @param array $menus
     */
    public static function topMenu($menus)
    {
        $_menus = self::getTopMenus();
        if(!empty($_menus)){
            return $_menus;
        }
        self::setPermissions();
        $menus = self::recursiveInitMenu($menus);
        \Yii::$app->session->set(self::CACHE_TOP_MENU, $menus);
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
        return \Yii::$app->session->get(self::CACHE_MENU);
    }
    public static function getTopMenus()
    {
        return \Yii::$app->session->get(self::CACHE_TOP_MENU);
    }
}