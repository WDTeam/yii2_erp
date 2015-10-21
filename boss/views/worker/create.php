<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */

/*tabs-x 切换
nav-x 导航
editable 弹出
helper-functions/html icon
yii2-dynagrid
*/

$this->title = Yii::t('app', '录入新阿姨', [
    'modelClass' => 'Worker',
]);
$this->params['breadcrumbs'][] = ['label' => '阿姨管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$worker_ext->worker_is_health=1;
$worker_ext->worker_is_insurance=1;
$worker_ext->worker_sex=0;
$worker->worker_type=1;
$worker->worker_rule_id=1;

?>
<div class="worker-create">

    <?= $this->render('_form', [
        'worker' => $worker,
        'worker_ext'=>$worker_ext
    ]) ?>

</div>
