<?php
/*
 * BOSS 自动派单运行服务实例
 * @author 张旭刚<zhangxugang@corp.1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
*/
namespace boss\controllers\order;

use Yii;
use core\models\Order\Order;
use boss\models\AutoAssignSerach;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\components\BaseAuthController;
use autoassign\ClientCommand;

/**
 * AutoOrderController implements the CRUD actions for Order model.
 */
class AutoAssignController extends BaseAuthController
{
    public $config;
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
        $this->config = require_once(__DIR__ . '/../../../autoassign/autoassign.config.php');
        $data = (array)json_decode(Yii::$app->redis->get($this->config['_REDIS_SERVER_RUN_STATUS_']));
        
        return $this->render('index', ['data' => $data]);
    }
}
