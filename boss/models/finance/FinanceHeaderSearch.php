<?php
/**
* 控制器  对账表头配置管理
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/
namespace boss\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\finance\FinanceHeader;

/**
 * FinanceHeaderSearch represents the model behind the search form about `dbbase\models\FinanceHeader`.
 */
class FinanceHeaderSearch extends FinanceHeader
{
    public function rules()
    {
        return [
            [['id', 'finance_order_channel_id', 'finance_pay_channel_id', 'create_time', 'is_del'], 'integer'],
            [['finance_header_name','finance_header_title', 'finance_order_channel_name', 'finance_pay_channel_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceHeader::find();
       
      //  FinanceHeader::orderBy(['id'=>SORT_DESC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,	
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        	'finance_header_title' =>trim($this->finance_header_title),
            'finance_order_channel_id' => $this->finance_order_channel_id,
            'finance_pay_channel_id' => $this->finance_pay_channel_id,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_header_name', $this->finance_header_name])
            ->andFilterWhere(['like', 'finance_order_channel_name', $this->finance_order_channel_name])
            ->andFilterWhere(['like', 'finance_pay_channel_name', $this->finance_pay_channel_name]);

        return $dataProvider;
    }
}
