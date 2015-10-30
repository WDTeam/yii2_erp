<?php
use yii\helpers\Html;
if(!empty($versions)){
    echo '<div class="step3_versions_'.$platform['id'].'">';
    echo '<label class="control-label" for="operationadvertrelease-city_id">'.$platform['operation_platform_name'].'：</label>';
    echo Html::checkboxList('OperationAdvertRelease[version_id][]', null, $versions, ['platform_id' => $platform['id'], 'class' => 'platform_versions']);
    echo '<div>';
}