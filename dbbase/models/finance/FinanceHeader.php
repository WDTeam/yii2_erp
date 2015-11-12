<?php
/**
* 对账表头数据库映射关系 
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_header}}".
 *
 * @property integer $id
 * @property string $finance_header_name
 * @property integer $finance_order_channel_id
 * @property string $finance_order_channel_name
 * @property integer $finance_pay_channel_id
 * @property string $finance_pay_channel_name
 * @property integer $create_time
 * @property integer $is_del
 */
class FinanceHeader extends \yii\db\ActiveRecord
{
	public $finance_uplod_url;
	public $select_where;
	
	public static function selectname($wherename)
	{
		 
		switch ($wherename)
		{
			case 'order_channel_order_num':
				return '<font color="red">订单号</font>';
				break;
			case 'order_channel_promote':
				return '<font color="blue">渠道营销费</font>';
				break;
			case 'order_money':
				return '<font color="red">支付金额</font>';
				break;
			case 'refund':
				return '<font color="red">退款金额</font>';
				break;
			case 'decrease':
				return '<font color="blue">手续费</font>';
				break;
			case 'function_way':
				return '<font color="blue">状态分类</font>';
				break;
			default:
				return '<font color="purple">未选择</font>';
				break;
				
		}
	
	}
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_header}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_order_channel_id','finance_pay_channel_id', 'create_time', 'is_del','finance_header_key'], 'integer'], 
            [['finance_header_title'],'match','pattern'=>'/^[\w|\x{4e00}-\x{9fa5}]+$/u','message'=>'名字里面不能包含特殊符号'],  
            [['finance_header_name','finance_header_where','finance_order_channel_name', 'finance_pay_channel_name'], 'string', 'max' => 100]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'finance_header_key' => Yii::t('boss', '对应栏位'),
			'finance_header_title' => Yii::t('boss', '当前名称'),
			'finance_header_name' => Yii::t('boss', '表头名称'),
			'finance_header_where' => Yii::t('boss', '比对字段名称'),
			'finance_order_channel_id' => Yii::t('boss', '订单渠道id'),
            'finance_order_channel_name' => Yii::t('boss', '订单渠道名称'),
            'finance_pay_channel_id' => Yii::t('boss', '支付渠道id'),
            'finance_pay_channel_name' => Yii::t('boss', '支付渠道名称'),
			'create_time' => Yii::t('boss', '创建时间'),
			'finance_header_where' => Yii::t('boss', '比对字段'),
			'finance_uplod_url' => Yii::t('boss', '上传xls'),
			'is_del' => Yii::t('boss', '0 正常 1 删除'),
			
        ];
    }
}
