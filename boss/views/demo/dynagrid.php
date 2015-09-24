<?php
use kartik\dynagrid\DynaGrid;


$columns = [
    ['class'=>'kartik\grid\SerialColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],
    'id',
    'name',
    'province_name',
    'city_name',
    'county_name',
   'street', 
   'principal', 
   'tel', 
   'other_contact', 
   'bankcard_number', 
   'account_person', 
   'opening_bank', 
   'sub_branch', 
   'opening_address', 
   'bl_name', 
   'bl_type', 
   'bl_number', 
   'bl_person', 
   'bl_address', 
   'bl_create_time:datetime', 
   'bl_photo_url:url', 
   'bl_audit', 
   'bl_expiry_start', 
   'bl_expiry_end', 
   'bl_business:ntext', 
    [
        'class'=>'kartik\grid\BooleanColumn',
        'attribute'=>'audit_status',
        'vAlign'=>'middle',
    ],
    [
        'class'=>'kartik\grid\ActionColumn',
        'dropdown'=>false,
        'order'=>DynaGrid::ORDER_FIX_RIGHT
    ],
    ['class'=>'kartik\grid\CheckboxColumn', 'order'=>DynaGrid::ORDER_FIX_RIGHT],
];
echo DynaGrid::widget([
    'columns'=>$columns,
    'storage'=>DynaGrid::TYPE_COOKIE,
    'theme'=>'simple-default',
    'allowPageSetting'=>true,
    'showPersonalize'=>true,
    'gridOptions'=>[
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'panel'=>['heading'=>'<h3 class="panel-title">Library</h3>'],
    ],
    'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
]);