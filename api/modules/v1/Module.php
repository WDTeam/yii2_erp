<?php

namespace api\modules\v1;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function init()
    {
        parent::init();

        $this->modules = [
            'user' => [
                'class' => 'api\modules\v1\user\Model',
            ],
            'order' => [
                'class' => 'api\modules\v1\order\Module',
            ],

        ];
        // custom initialization code goes here
    }
}
