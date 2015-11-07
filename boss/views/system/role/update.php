<?php

$this->title = Yii::t('app', 'Update ') . Yii::t('app', '{name}', ['name' => $model->description]);
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('app', 'Roles'),
        'url' => ['index']
    ],
    $this->title
];

echo $this->render('_form', [
    'model' => $model,
    'permissions' => $permissions,
]);