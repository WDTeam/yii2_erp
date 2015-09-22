<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationCity;

/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property string $operation_city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationCity extends CommonOperationCity
{

}
