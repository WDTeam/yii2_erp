<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/21
 * Time: 17:33
 */

namespace dbbase\models\order;

use Yii;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    public function delete()
    {
        if($this->hasAttribute('isdel') && !$this->isNewRecord) {
            $this->isdel = 1;
            $this->save();
        }
    }
}