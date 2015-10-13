<?php 
use kartik\helpers\Html;
use boss\models\ShopManager;
?>

<?php 
echo Html::a('全部('.ShopManager::getTotal().')',[
    'index'
],[
    'class'=>'btn btn-success'
]);
echo ' ';
echo Html::a('黑名单('.ShopManager::getIsBlacklistCount().')',[
    'index','ShopManagerSearch[is_blacklist]'=>1
],[
    'class'=>'btn btn-success'
]);

// echo Html::a('待审核('.ShopManager::getAuditStatusCountByNumber(0).')',[
//     'index','ShopManagerSearch'=>['audit_status'=>0]
// ], [
//     'class'=>'btn btn-success'
// ]);

// echo Html::a('未验证通过('.ShopManager::getAuditStatusCountByNumber(2).')',[
//     'index','ShopManagerSearch'=>['audit_status'=>2]
// ],[
//     'class'=>'btn btn-success'
// ]);

// echo Html::a('验证通过('.ShopManager::getAuditStatusCountByNumber(1).')',[
//     'index','ShopManagerSearch'=>['audit_status'=>1]
// ],[
//     'class'=>'btn btn-success'
// ]);
?>

