<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationArea;

/**
 * This is the model class for table "{{%operation_area}}".
 *
 * @property integer $id
 * @property string $area_name
 * @property integer $parent_id
 * @property string $short_name
 * @property string $longitude
 * @property string $latitude
 * @property integer $level
 * @property string $position
 * @property integer $sort
 */
class CoreOperationArea extends CommonOperationArea
{

}
