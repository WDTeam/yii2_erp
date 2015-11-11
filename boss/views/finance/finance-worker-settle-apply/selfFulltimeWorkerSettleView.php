<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use core\models\finance\FinanceWorkerOrderIncomeSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨结算');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
            <?php

                 echo $this->render('workerSettleCommonView', ['model'=>$model,'orderDataProvider'=>$orderDataProvider,'cashOrderDataProvider'=>$cashOrderDataProvider,'taskDataProvider'=>$taskDataProvider,'compensateDataProvider'=>$compensateDataProvider]);
            ?>
    </div>
</div>
