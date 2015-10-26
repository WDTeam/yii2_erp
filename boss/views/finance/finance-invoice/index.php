<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceInvoiceSearch $searchModel
 */

$this->title = '发票管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoice-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Finance Invoice', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'finance_invoice_serial_number',
            'finance_invoice_customer_tel',
            'finance_invoice_worker_tel',
            'pay_channel_pay_id',
//            'pay_channel_pay_title', 
//            'finance_invoice_pay_status', 
//            'admin_confirm_uid', 
//            'finance_invoice_enrolment_time:datetime', 
//            'finance_invoice_money', 
//            'finance_invoice_title', 
//            'finance_invoice_address', 
//            'finance_invoice_status', 
//            'finance_invoice_check_id', 
//            'finance_invoice_number', 
//            'finance_invoice_service_money', 
//            'finance_invoice_corp_email:email', 
//            'finance_invoice_corp_address', 
//            'finance_invoice_corp_name', 
//            'finance_invoice_district_id', 
//            'classify_id', 
//            'classify_title', 
//            'create_time', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['/finance/finance-invoice/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
