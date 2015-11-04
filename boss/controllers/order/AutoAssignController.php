<?php
/*
 * BOSS 自动派单运行服务实例
 * @author 张旭刚<zhangxugang@corp.1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
*/
namespace boss\controllers\order;

use autoassign\ClientCommand;

use core\models\Order\Order;

use boss\components\BaseAuthController;
use boss\models\AutoAssignSerach;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

define("WEBPATH", str_replace("\\","/", __DIR__));
define("CONFIG_PATH", WEBPATH."/autoassign.config.php");

/**
 * AutoOrderController implements the CRUD actions for Order model.
 */
class AutoAssignController extends BaseAuthController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $config = require(CONFIG_PATH);
        $srvInfo = (array)json_decode(Yii::$app->redis->get('_REDIS_SERVER_RUN_STATUS_'));
        $srvIsSuspend = json_decode(Yii::$app->redis->get('REDIS_IS_SERVER_SUSPEND'));
        return $this->render('index', ['srvInfo' => $srvInfo,'config'=>$config,'srvIsSuspend'=>$srvIsSuspend]);
    }
}
