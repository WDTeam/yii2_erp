<?php

namespace core\models\operation;

use Yii;
use common\models\operation\OperationSpecGoods;
use common\models\operation\OperationGoods;

/**
 * This is the model class for table "{{%operation_spec_goods}}".
 *
 * @property integer $id
 * @property integer $operation_goods_id
 * @property string $operation_goods_name
 * @property string $operation_spec_goods_no
 * @property integer $operation_spec_id
 * @property integer $operation_spec_name
 * @property integer $operation_spec_value
 * @property integer $operation_spec_goods_lowest_consume_number
 * @property string $operation_spec_goods_sell_price
 * @property string $operation_spec_goods_market_price
 * @property string $operation_spec_goods_cost_price
 * @property string $operation_spec_goods_settlement_price
 * @property integer $operation_spec_goods_commission_mode
 * @property string $operation_spec_goods_commission
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationSpecGoods extends OperationSpecGoods
{
    /**
     * 插入规格商品
     */
    public static function setSpecGoods($data){
        if(!empty($data)){
            self::deleteAll(['operation_goods_id' => $data['operation_goods_id']]);
            $specinfo = [
                'operation_goods_id' => $data['operation_goods_id'], //商品id
                'operation_goods_name' => $data['operation_goods_name'], //商品名称
                'operation_spec_id' => $data['operation_spec_id'], //规格id
                'operation_spec_name' => $data['operation_spec_name'], //规格名称
                'created_at' => time(), //创建时间
                'updated_at' => time(), //更新时间
            ];
            $d = array();
            $price = $data['operation_spec_goods_sell_price'][0];
            $pricekey = 0;
            foreach ((array)$data['operation_spec_goods_no'] as $key => $value) {
                $d[$key] = $specinfo;
                $d[$key]['operation_spec_goods_no'] = $value; //商品货号
                $d[$key]['operation_spec_value'] = $data['operation_spec_value'][$key]; //规格属性
                $d[$key]['operation_spec_goods_lowest_consume_number'] = $data['operation_spec_goods_lowest_consume_number'][$key]; //最低消费数量
                $d[$key]['operation_spec_strategy_unit'] = $data['operation_spec_strategy_unit'][$key]; //计量单位
                if ($data['operation_spec_goods_sell_price'][$key] < $price) {
                    $pricekey = $key;
                    $price = $data['operation_spec_goods_sell_price'][$key];
                }
                $d[$key]['operation_spec_goods_sell_price'] = $data['operation_spec_goods_sell_price'][$key]; //商品销售价格
                $d[$key]['operation_spec_goods_market_price'] = $data['operation_spec_goods_market_price'][$key]; //商品市场价
                $d[$key]['operation_spec_goods_cost_price'] = $data['operation_spec_goods_cost_price'][$key]; //商品成本价格
                $d[$key]['operation_spec_goods_settlement_price'] = $data['operation_spec_goods_settlement_price'][$key]; //商品结算价格
                $d[$key]['operation_spec_goods_commission_mode'] = $data['operation_spec_goods_commission_mode'][$key]; //收取佣金方式（1: 百分比 2: 金额）

                if ($data['operation_spec_goods_commission_mode'][$key] == 1) {
                    $d[$key]['operation_spec_goods_commission'] = $data['operation_spec_goods_commissionpercent'][$key]; //佣金值 百分比
                } else {
                    $d[$key]['operation_spec_goods_commission'] = $data['operation_spec_goods_commissiondigital'][$key]; //佣金值 金额
                }
            }
            $fields = [
                'operation_goods_id',
                'operation_goods_name',
                'operation_spec_id',
                'operation_spec_name',
                'created_at',
                'updated_at',
                'operation_spec_goods_no',
                'operation_spec_value',
                'operation_spec_goods_lowest_consume_number',
                'operation_spec_strategy_unit',
                'operation_spec_goods_sell_price',
                'operation_spec_goods_market_price',
                'operation_spec_goods_cost_price',
                'operation_spec_goods_settlement_price',
                'operation_spec_goods_commission_mode',
                'operation_spec_goods_commission',
            ];
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $d)->execute();
            /** 返回规格中销售价格最小的属性 **/
            $minspecdata = array();
            $minspecdata['operation_goods_no'] = $data['operation_spec_goods_no'][$pricekey]; //商品货号
            $minspecdata['operation_goods_price'] = $data['operation_spec_goods_sell_price'][$pricekey]; //商品销售价格
            $minspecdata['operation_goods_balance_price'] = $data['operation_spec_goods_settlement_price'][$pricekey]; //阿姨结算价格
            $minspecdata['operation_goods_lowest_consume'] = $minspecdata['operation_goods_price']*$data['operation_spec_goods_lowest_consume_number'][$pricekey]; //最低消费（商品销售价格＊最低消费数量）
            $minspecdata['operation_goods_market_price'] = $data['operation_spec_goods_market_price'][$pricekey]; //商品市场价格
            $minspecdata['operation_spec_info'] = serialize(['id' => $data['operation_spec_id'], 'operation_spec_name' => $data['operation_spec_name'], 'operation_spec_values' => $data['operation_spec_value']]); //序列化存储规格

            Yii::$app->db->createCommand()->update(CommonOperationGoods::tableName(), $minspecdata, ['id' => $data['operation_goods_id']])->execute();
        }
    }
    
    public static function getSpecGoods($operation_goods_id){
        return self::find()->asArray()->where(['operation_goods_id' => $operation_goods_id])->All();
    }
}
