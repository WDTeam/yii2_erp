<?php

namespace core\models\CustomerTransRecord;

use common\models\CustomerTransRecordLog;
use common\models\FinancePayChannel;
use Yii;
use yii\behaviors\TimestampBehavior;

class CustomerTransRecord extends \common\models\CustomerTransRecord
{
    /**
     * 创建交易记录
     * @param $data 数据
     */
    public static function createRecord($data)
    {
        //记录日志
        $obj = new self;
        $obj->on('insertLog',[new CustomerTransRecordLog(),'insertLog'],$data);
        $obj->trigger('insertLog');

        //验证是否存在场景
        if(empty($data['scenario'])){
            return false;
        }

        //使用场景
        $obj->scenario = $data['scenario'];
        $obj->attributes = $data;
        return $obj->add();
    }
}


