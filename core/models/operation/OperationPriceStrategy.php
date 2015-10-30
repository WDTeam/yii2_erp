<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationPriceStrategy as CommonOperationPriceStrategy;

/**
 * This is the model class for table "{{%operation_price_strategy}}".
 *
 * @property integer $id
 * @property string $operation_price_strategy_name
 * @property string $operation_price_strategy_unit
 * @property string $operation_price_strategy_lowest_consume_unit
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationPriceStrategy extends CommonOperationPriceStrategy
{

}
