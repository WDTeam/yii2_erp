<?php
/**
 * @author CoLee
 */
namespace console\controllers;

use yii\console\Controller;

class ImportDataController extends Controller
{
    public function actionIndex()
    {
        return true;
        $db = \Yii::$app->db;
        //导入 shop_manager
        $rows = $db->createCommand(
            "select * from imp_shop_manager"    
        )->queryAll();
        foreach ($rows as $row){
            $province_name = $row['province_name'];
            $row['province_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$province_name}%' 
                AND level=1
            ")->queryScalar();
            $row['city_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['city_name']}%'
                AND level=2
            ")->queryScalar();
            $row['county_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['county_name']}%'
                AND level=2
            ")->queryScalar();
            unset($row['username']);
            unset($row['password']);
            unset($row['province_name']);
            unset($row['city_name']);
            unset($row['county_name']);
            $res = $db->createCommand()->insert('{{%shop_manager}}', $row)->execute();
            var_dump($res);
        }
    }
    
    public function actionShop()
    {
        return true;
        $db = \Yii::$app->db;
        // 导入 shop
        $rows = $db->createCommand(
            "select * from imp_shop"
        )->queryAll();
        foreach ($rows as $row){
            $province_name = $row['province_name'];
            $row['province_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$province_name}%'
                AND level=1
                ")->queryScalar();
            $row['city_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['city_name']}%'
                AND level=2
                ")->queryScalar();
            $row['county_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['county_name']}%'
                AND level=2
                ")->queryScalar();
            unset($row['username']);
            unset($row['password']);
            unset($row['province_name']);
            unset($row['city_name']);
            unset($row['county_name']);
        
            if(empty($row['shop_manager_id'])){
                $row['shop_manager_id'] = $row['name'];
            }
            $row['shop_manager_id'] = $db->createCommand("
            select id from {{%shop_manager}} where name='{$row['shop_manager_id']}'
            ")->queryScalar();
        
            $res = $db->createCommand()->insert('{{%shop}}', $row)->execute();
            var_dump($res);
        }
    }
    
    public function actionUser()
    {
        return true;
        $db = \Yii::$app->db;
        $rows = $db->createCommand(
            "select * from imp_user where is_used=1"
        )->queryAll();
        foreach ($rows as $row){
            $row['id'] = $row['ID'];
            $row['email'] = empty($row['email'])?null:$row['email'];
            unset($row['ID']);
            unset($row['role']);
            unset($row['is_used']);
            $res = $db->createCommand()->insert('{{%system_user}}', $row)->execute();
            var_dump($res);
        }
    }
}