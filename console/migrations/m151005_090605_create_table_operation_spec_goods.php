<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151005_090605_create_table_operation_spec_goods extends Migration
{
    public function Up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'规格商品表\'';
        }

        $this->createTable('{{%operation_spec_goods}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_goods_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'商品编号\'',
            'operation_goods_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商品名称\'',

            'operation_spec_goods_no' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'商品规格货号\'',

            'operation_spec_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'规格编号\'',
            'operation_spec_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'规格名称\'',
            'operation_spec_value' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'规格属性\'',

            'operation_spec_goods_lowest_consume_number' => Schema::TYPE_INTEGER . '(11) DEFAULT 1 COMMENT \'最低消费数量\'',

            'operation_spec_goods_sell_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'商品销售价格\'',
            'operation_spec_goods_market_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'商品市场价格\'',
            'operation_spec_goods_cost_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'商品成本价格\'',
            'operation_spec_goods_settlement_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'商品结算价格\'',

            'operation_spec_goods_commission_mode' => Schema::TYPE_INTEGER . '(1) DEFAULT 2 COMMENT \'收取佣金方式（1: 百分比 2: 金额）\'',
            'operation_spec_goods_commission' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'佣金值\'',

            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'', 
        ], $tableOptions);

//         $this->execute(
//             "INSERT INTO {{%operation_spec_goods}} (`id`, `operation_goods_id`, `operation_goods_name`, `operation_spec_goods_no`, `operation_spec_id`, `operation_spec_name`, `operation_spec_value`, `operation_spec_goods_lowest_consume_number`, `operation_spec_goods_sell_price`, `operation_spec_goods_market_price`, `operation_spec_goods_cost_price`, `operation_spec_goods_settlement_price`, `operation_spec_goods_commission_mode`, `operation_spec_goods_commission`, `created_at`, `updated_at`)
// VALUES
// 	(49, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474251', 3, 'iphone', '16G合约机', 9, 92.0000, 91.0000, 93.0000, 94.0000, 1, 90.0000, 1444248319, 1444248319),
// 	(50, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474252', 3, 'iphone', '32G非合约机', 8, 82.0000, 81.0000, 83.0000, 84.0000, 1, 80.0000, 1444248319, 1444248319),
// 	(51, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474253', 3, 'iphone', '64合约机', 7, 72.0000, 71.0000, 73.0000, 74.0000, 2, 70.0000, 1444248319, 1444248319),
// 	(52, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474254', 3, 'iphone', '128G合约机', 6, 62.0000, 61.0000, 63.0000, 64.0000, 2, 60.0000, 1444248319, 1444248319);"
//         );

// $this->execute(
//     "INSERT INTO {{%operation_spec_goods}} (`id`, `operation_goods_id`, `operation_goods_name`, `operation_spec_goods_no`, `operation_spec_id`, `operation_spec_name`, `operation_spec_value`, `operation_spec_goods_lowest_consume_number`, `operation_spec_goods_sell_price`, `operation_spec_goods_market_price`, `operation_spec_goods_cost_price`, `operation_spec_goods_settlement_price`, `operation_spec_goods_commission_mode`, `operation_spec_goods_commission`, `created_at`, `updated_at`) VALUES (49, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474251', 3, 'iphone', '16G合约机', 9, 92.0000, 91.0000, 93.0000, 94.0000, 1, 90.0000, 1444248319, 1444248319);
//      INSERT INTO {{%operation_spec_goods}} (`id`, `operation_goods_id`, `operation_goods_name`, `operation_spec_goods_no`, `operation_spec_id`, `operation_spec_name`, `operation_spec_value`, `operation_spec_goods_lowest_consume_number`, `operation_spec_goods_sell_price`, `operation_spec_goods_market_price`, `operation_spec_goods_cost_price`, `operation_spec_goods_settlement_price`, `operation_spec_goods_commission_mode`, `operation_spec_goods_commission`, `created_at`, `updated_at`) VALUES (50, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474252', 3, 'iphone', '32G非合约机', 8, 82.0000, 81.0000, 83.0000, 84.0000, 1, 80.0000, 1444248319, 1444248319);
//      INSERT INTO {{%operation_spec_goods}} (`id`, `operation_goods_id`, `operation_goods_name`, `operation_spec_goods_no`, `operation_spec_id`, `operation_spec_name`, `operation_spec_value`, `operation_spec_goods_lowest_consume_number`, `operation_spec_goods_sell_price`, `operation_spec_goods_market_price`, `operation_spec_goods_cost_price`, `operation_spec_goods_settlement_price`, `operation_spec_goods_commission_mode`, `operation_spec_goods_commission`, `created_at`, `updated_at`) VALUES (51, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474253', 3, 'iphone', '64合约机', 7, 72.0000, 71.0000, 73.0000, 74.0000, 2, 70.0000, 1444248319, 1444248319);
//      INSERT INTO {{%operation_spec_goods}} (`id`, `operation_goods_id`, `operation_goods_name`, `operation_spec_goods_no`, `operation_spec_id`, `operation_spec_name`, `operation_spec_value`, `operation_spec_goods_lowest_consume_number`, `operation_spec_goods_sell_price`, `operation_spec_goods_market_price`, `operation_spec_goods_cost_price`, `operation_spec_goods_settlement_price`, `operation_spec_goods_commission_mode`, `operation_spec_goods_commission`, `created_at`, `updated_at`) VALUES (52, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474254', 3, 'iphone', '128G合约机', 6, 62.0000, 61.0000, 63.0000, 64.0000, 2, 60.0000, 1444248319, 1444248319);"
//     );

       $this->execute(
           "INSERT INTO {{%operation_spec_goods}} (`id`, `operation_goods_id`, `operation_goods_name`, `operation_spec_goods_no`, `operation_spec_id`, `operation_spec_name`, `operation_spec_value`, `operation_spec_goods_lowest_consume_number`, `operation_spec_goods_sell_price`, `operation_spec_goods_market_price`, `operation_spec_goods_cost_price`, `operation_spec_goods_settlement_price`, `operation_spec_goods_commission_mode`, `operation_spec_goods_commission`, `created_at`, `updated_at`) VALUES (49, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474251', 3, 'iphone', '16G合约机', 9, 92.0000, 91.0000, 93.0000, 94.0000, 1, 90.0000, 1444248319, 1444248319),	(50, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474252', 3, 'iphone', '32G非合约机', 8, 82.0000, 81.0000, 83.0000, 84.0000, 1, 80.0000, 1444248319, 1444248319),	(51, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474253', 3, 'iphone', '64合约机', 7, 72.0000, 71.0000, 73.0000, 74.0000, 2, 70.0000, 1444248319, 1444248319), (52, 1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474254', 3, 'iphone', '128G合约机', 6, 62.0000, 61.0000, 63.0000, 64.0000, 2, 60.0000, 1444248319, 1444248319);"
       );
    }

    public function down()
    {
        $this->dropTable('{{%operation_spec_goods}}');
    }
}
