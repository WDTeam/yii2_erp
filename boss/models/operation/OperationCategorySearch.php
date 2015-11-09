<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\operation\OperationCategory;

/**
 * OperationCategorySearch represents the model behind the search form about `dbbase\models\OperationCategory`.
 */
class OperationCategorySearch extends OperationCategory
{
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['operation_category_name'], 'string'],
        ];
    }

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_category_name' => Yii::t('app', '品类名称'),
            'operation_goods_name'=>Yii::t('app','商品名称'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {

        $query = new \yii\db\Query();;
        $query = $query->select([
            'goods.id as goods_id',
            'category.id',
            'category.operation_category_name',
            'goods.operation_goods_name',
            'goods.operation_goods_english_name'
        ])
            ->from('{{%operation_goods}} as goods')
            ->rightJoin('{{%operation_category}} as category','goods.operation_category_id = category.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'category.operation_category_name', $this->operation_category_name]);

        return $dataProvider;
    }
}
