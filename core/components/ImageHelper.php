<?php
/**
 * 图片管理类
 * 这里使用的是七牛云存储
 * @see https://github.com/crazyfd/yii2-qiniu
 * @author CoLee
 */
namespace core\components;

use yii\base\Component;
use crazyfd\qiniu\Qiniu;
class ImageHelper extends Component
{
    public $accessKey = 'kaMuZPkS_f_fxcfsDKET0rTst-pW6Ci7GMlakffw';
    public $secretKey = 'HEMGszOQBpQEC_GMqFqT_mwQW0ypQoE0Y3uhCllq';
    public $domain = '7b1f97.com1.z0.glb.clouddn.com';
    public $bucket = 'bjzhichangmusic';

    public function __call($name, $params)
    {
        $qiniu = new Qiniu(
            $this->accessKey,
            $this->secretKey,
            $this->domain,
            $this->bucket
        );
        $res = call_user_func_array([$qiniu, $name], $params);
        return $res;
    }
}