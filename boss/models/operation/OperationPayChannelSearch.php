<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\operation\OperationPayChannel;

/**
 * OperationPayChannelSearch represents the model behind the search form about `dbbase\models\operation\OperationPayChannel`.
 */
class OperationPayChannelSearch extends OperationPayChannel
{
    public function rules()
    {
        return [
            [['id', 'operation_pay_channel_type', 'system_user_id', 'create_time', 'is_del'], 'integer'],
            [['operation_pay_channel_name', 'operation_pay_channel_rate', 'system_user_name'], 'safe'],
        ];
    }

    
    /**
     * 订单配置项
     * @date: 2015-11-10
     * @author: peak pan
     * @return:
     **/
    public static  function configorder()
    {
    	return ['1' => '在线支付', '2' => 'e家洁','3'=>'第三方'];
    }
    
    
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationPayChannel::find();
        $query= $query->orderBy('operation_pay_channel_type asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'operation_pay_channel_type' => $this->operation_pay_channel_type,
            'system_user_id' => $this->system_user_id,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'operation_pay_channel_name', $this->operation_pay_channel_name])
            ->andFilterWhere(['like', 'operation_pay_channel_rate', $this->operation_pay_channel_rate])
            ->andFilterWhere(['like', 'system_user_name', $this->system_user_name]);

        return $dataProvider;
    }
}
