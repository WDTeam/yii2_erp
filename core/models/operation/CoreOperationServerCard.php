<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationServerCard;
/**
 * This is the model class for table "{{%server_card}}".
 *
 * @property string $id
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $par_value
 * @property string $reb_value
 * @property integer $use_scope
 * @property integer $valid_days
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationServerCard extends CommonOperationServerCard
{
    
}
