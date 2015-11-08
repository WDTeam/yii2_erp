<?php
namespace boss\components;

use yii\base\Component;
class RbacHelper extends Component
{
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
            }
            if(isset($menu['items'])){
                $menu['items'] = self::recursiveInitMenu($menu['items']);
            }
            $menus[$key] = $menu;
        }
        return $menus;
    }
    /**
     * 处理菜单
     * @param array $menus
     */
    public static function menu($menus)
    {
        $menus = self::recursiveInitMenu($menus);
        \Yii::$app->cache->set('boss-menus', $menus);
        return $menus;
    }
    
}