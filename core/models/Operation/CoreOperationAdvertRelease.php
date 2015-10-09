<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationAdvertRelease;
/**
 * This is the model class for table "{{%operation_advert_release}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_id
 * @property string $operation_advert_position_name
 * @property integer $operation_advert_content_id
 * @property integer $operation_advert_content_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationAdvertRelease extends CommonOperationAdvertRelease
{
    
}
