<?php
/**
* 控制器  订单搜索类
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-10
* @author: peak pan 
* @version:1.0
*/
namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\operation\OperationOrderChannel;


class OperationOrderChannelSearch extends OperationOrderChannel
{
    public function rules()
    {
        return [
            [['id', 'operation_order_channel_type', 'system_user_id', 'create_time', 'is_del'], 'integer'],
            [['operation_order_channel_name', 'operation_order_channel_rate', 'system_user_name'], 'safe'],
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
    	return ['1' => 'e家洁', '2' => 'POP','3'=>'BOSS'];
    } 
    
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationOrderChannel::find();
        $query= $query->orderBy('operation_order_channel_type asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'operation_order_channel_type' => $this->operation_order_channel_type,
            'system_user_id' => $this->system_user_id,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'operation_order_channel_name', $this->operation_order_channel_name])
            ->andFilterWhere(['like', 'operation_order_channel_rate', $this->operation_order_channel_rate])
            ->andFilterWhere(['like', 'system_user_name', $this->system_user_name]);

        return $dataProvider;
    }
}
