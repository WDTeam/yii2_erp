<?php

namespace dbbase\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_selected_service".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $scene
 * @property string $area
 * @property string $sub_area
 * @property string $standard
 * @property string $price
 * @property integer $unit
 * @property string $created_at
 * @property string $remark
 */
class OperationSelectedService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_selected_service}}';
    }
}
