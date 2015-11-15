<?php
/**
 * @author CoLee
 */
namespace boss\components;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use core\models\auth\AuthItem;
class RbacHelper extends Component
{
    const CACHE_MENU = 'boss-menus';
    const CACHE_TOP_MENU = 'boss-top-menus';
    const CONFIG_VERSION = 'boss-rbac-config-version';

    /**
     * 处理左菜单
     * @param array $menus
     */
    public static function menu($menus)
    {
        $_menus = self::getMenus();
        $cver = \Yii::$app->cache->get(self::CONFIG_VERSION);
        $sver = \Yii::$app->session->get(self::CONFIG_VERSION.\Yii::$app->session->id);
        if(empty($_menus) || $cver!=$sver){
            $menus = self::recursiveInitMenu($menus);
            $cver = \Yii::$app->cache->get(self::CONFIG_VERSION);
            \Yii::$app->session->set(self::CONFIG_VERSION.\Yii::$app->session->id, $cver);
            \Yii::$app->session->set(self::CACHE_MENU.\Yii::$app->session->id, $menus);
            return $menus;
        }
        return $_menus;
    }
    /**
     * 处理顶部菜单
     * @param array $menus
     */
    public static function topMenu($menus)
    {
        $_menus = self::getTopMenus();
        if(empty($_menus)){
            $menus = self::recursiveInitMenu($menus);
            \Yii::$app->session->set(self::CACHE_TOP_MENU.\Yii::$app->session->id, $menus);
            return $menus;
        }
        return $_menus;
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
                    $admin = $auth->getRole(AuthItem::SYSTEM_ROLE_SUPER_ADMIN);
                    $auth->addChild($admin, $permission);
                }
                $menu['visible'] = \Yii::$app->user->can($name);
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
        return \Yii::$app->session->get(self::CACHE_MENU.\Yii::$app->session->id);
    }
    public static function getTopMenus()
    {
        return \Yii::$app->session->get(self::CACHE_TOP_MENU.\Yii::$app->session->id);
    }
    /**
     * 权限修改时调用此方法
     */
    public static function updateConfigVersion()
    {
        $key = time();
        \Yii::$app->cache->set(self::CONFIG_VERSION, $key);
    }
}