<?php
namespace boss\models;

use yii\web\HttpException;
use yii;
use yii\helpers\Html;
class Courseware extends \common\models\Courseware
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'image' => Yii::t('app', '缩略图'),
            'url' => Yii::t('app', '视频URL'),
            'name' => Yii::t('app', '视频名称'),
            'pv' => Yii::t('app', '播放次数'),
            'order_number' => Yii::t('app', '排序'),
            'classify_id' => Yii::t('app', '服务技能'),
        ];
    }
    /**
     * 读取奥点视频列表
     * @throws HttpException
     */
    public static function getVideoList()
    {
        $url = 'http://openapi.aodianyun.com/v2/VOD.GetUploadVodList';
        $params = json_encode([
            "access_id"=>"702649135618",
            "access_key"=>"vEejv7d56No713Rh30ACGIdw3qkK17Hd",
            "num"=>200
        ]);
        $post_data = http_build_query(['parameter'=>$params]);
        $ch = \curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $data=  curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data,true);
        if(isset($data['Flag']) && $data['Flag']==100){
            return $data['List'];
        }else{
            throw new HttpException(401, '读取奥点视频列表失败！');
        }
    }
    
}