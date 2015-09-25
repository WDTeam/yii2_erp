<?php 
use kartik\helpers\Html;
use boss\models\ShopManager;
?>
<?php echo Html::a('待审核('.ShopManager::getAuditStatusCountByNumber(0).')',[
    'index','ShopSearch'=>['audit_status'=>0]
], [
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('未验证通过('.ShopManager::getAuditStatusCountByNumber(2).')',[
    'index','ShopSearch'=>['audit_status'=>2]
],[
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('验证通过('.ShopManager::getAuditStatusCountByNumber(1).')',[
    'index','ShopSearch'=>['audit_status'=>1]
],[
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('黑名单('.ShopManager::getIsBlacklistCount().')',[
    'index','ShopSearch[is_blacklist]'=>0
],[
    'class'=>'btn btn-success'
]);?>