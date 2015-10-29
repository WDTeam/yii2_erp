<?php

namespace restapi\modules\user;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\user\controllers';

    public function init()
    {
        parent::init();

        $this->modules = [
            'v1' => [
                'class' => 'api\modules\user\v1\Module',
            ]
        ];
        // custom initialization code goes here
    }
}
