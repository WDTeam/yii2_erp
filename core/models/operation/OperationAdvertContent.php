<?php

namespace core\models\operation;

use Yii;
use common\models\operation\OperationAdvertContent as CommonOperationAdvertContent;

/**
 * This is the model class for table "{{%operation_advert_content}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $operation_advert_start_time
 * @property integer $operation_advert_end_time
 * @property integer $operation_advert_online_time
 * @property integer $operation_advert_offline_time
 * @property string $operation_advert_picture
 * @property string $operation_advert_url
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertContent extends CommonOperationAdvertContent
{
    
}
