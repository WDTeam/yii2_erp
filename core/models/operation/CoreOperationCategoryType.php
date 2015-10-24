<?php

namespace core\models\operation;

use Yii;
use common\models\operation\CommonOperationCategoryType;
/**
 * This is the model class for table "{{%operation_category_type}}".
 *
 * @property integer $id
 * @property string $operation_category_type_name
 * @property integer $operation_category_id
 * @property string $operation_category_name
 * @property string $operation_category_type_introduction
 * @property string $operation_category_type_english_name
 * @property string $operation_category_type_start_time
 * @property string $operation_category_type_end_time
 * @property string $operation_category_type_service_time_slot
 * @property integer $operation_category_type_service_interval_time
 * @property integer $operation_price_strategy_id
 * @property string $operation_price_strategy_name
 * @property string $operation_category_type_price
 * @property string $operation_category_type_balance_price
 * @property string $operation_category_type_additional_cost
 * @property string $operation_category_type_lowest_consume
 * @property string $operation_category_type_price_description
 * @property string $operation_category_type_market_price
 * @property string $operation_tags
 * @property string $operation_category_type_app_ico
 * @property string $operation_category_type_pc_ico
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationCategoryType extends CommonOperationCategoryType
{

}
