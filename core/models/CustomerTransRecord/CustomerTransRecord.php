<?php

namespace core\models\CustomerTransRecord;

use common\models\CustomerTransRecordLog;
use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\CustomerTransRecord as CustomerTransRecordModel;

class CustomerTransRecord extends CustomerTransRecordModel
{
    /**
     * 创建交易记录
     * @param $data 数据
     */
    public static function createRecord($data)
    {
        //验证之前将数据插入记录表
        $model = new CustomerTransRecordLog();
        $model->attributes = $data;
        $model->validate();
        $model->insert(false);

        if(empty($data['scenario'])){
            return false;
        }
        $model = new CustomerTransRecord();
        //使用场景
        $model->scenario = $data['scenario'];
        $model->attributes = $data;
        return $model->add();
    }
}
