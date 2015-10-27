<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $cateid
 * @property string $catename
 * @property string $description
 */
class Category extends \common\models\Category
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string','max'=>200],
           // [['description'], 'required'],
            [['catename'], 'string', 'max' => 20],
            [['catename'], 'required',]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cateid' => '分类编号',
            'catename' => '分类名称',
            'description' => '分类描述',
        ];
    }
    
    /**
     * 所有服务分类
     * @return K-V array
     */
    public static function getAllClassifys()
    {
        $cates = self::find()->all();
        foreach ($cates as $cate){
            $res[$cate->cateid] = $cate['catename'];
        }
        return $res;
    }
}
