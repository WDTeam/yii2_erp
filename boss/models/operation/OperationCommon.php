<?php

namespace boss\models\operation;

use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

/**
 * @property integer $isdel
 */
class OperationCommon extends yii\base\Model
{

    public static function getPhotoShow($photo_path)
    {
        if($photo_path){
            return \yii\helpers\Html::img($photo_path, ['class'=>'file-preview-image']);
        }
    }

}
