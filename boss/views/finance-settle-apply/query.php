<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '结算查询');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i>结算查询</h3>
    </div>

    <div class="panel-body">
        <?php

        echo $this->render('_search', ['model' => $searchModel]);
        ?>

    </div>
    </div>
    <p>
    </p>
