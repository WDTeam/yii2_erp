<?php

use yii\bootstrap\Modal;
/**
 * Created by PhpStorm.
 * User: wzg
 * Date: 15-10-20
 * Time: 下午8:14
 */

Modal::begin([
    'header' => '<h4 class="modal-title">封号操作</h4>',
    'toggleButton' => ['label' => '<i ></i>封号', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">休假操作</h4>',
    'toggleButton' => ['label' => '<i ></i>休假', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">事假操作</h4>',
    'toggleButton' => ['label' => '<i ></i>事假', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">黑名单操作</h4>',
    'toggleButton' => ['label' => '<i ></i>黑名单', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">离职操作</h4>',
    'toggleButton' => ['label' => '<i ></i>离职', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>