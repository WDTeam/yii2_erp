<?php
namespace core\behaviors;

use yii\base\Behavior;
use yii\base\InvalidValueException;
use yii\base\Object;
use core\models\shop\ShopStatus;
use common\models\ActiveRecord;
class ShopStatusBehavior extends Behavior
{
    const CHANGE_STATUS = 'change:status';
    const CHANGE_BLACKLIST = 'change:blacklist';
    
    public $model_name;
    
    public function init()
    {
        if(empty($this->model_name)){
            throw new InvalidValueException('model_name 不能为空');
        }
    }
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE=> 'afterUpdate',
            self::CHANGE_STATUS=> 'changeStatus',
            self::CHANGE_BLACKLIST=> 'changeBlacklist'
        ];
    }
    
    public function afterUpdate($event)
    {
        $this->owner->trigger(self::CHANGE_STATUS);
        $this->owner->trigger(self::CHANGE_BLACKLIST);
    }
    
    public function changeStatus($event)
    {
        $last_status = ShopStatus::find()->select(['status_number'])
        ->where([
            'model_id'=>$this->owner->id,
            'model_name'=>$this->model_name,
            'status_type'=>1
        ])->orderBy('created_at DESC')->scalar();
        if($last_status==$this->owner->audit_status)
        {
            return true;
        }
        $status = new ShopStatus();
        $status->model_id = $this->owner->id;
        $status->status_number = $this->owner->audit_status;
        $status->model_name = $this->model_name;
        $status->status_type = 1;
        $status->created_at = time();
        $status->cause = $this->owner->cause;
        return $status->save();
    }
    
    private $_cause;
    public function setCause($cause)
    {
        $this->_cause = $cause;
    }
    public function getCause() {
        return $this->_cause;
    }
    
    public function changeBlacklist($event)
    {
        $last_status = ShopStatus::find()->select(['status_number'])
        ->where([
            'model_id'=>$this->owner->id,
            'model_name'=>$this->model_name,
            'status_type'=>2
        ])->orderBy('created_at DESC')->scalar();
        if($last_status==$this->owner->is_blacklist)
        {
            return true;
        }
        $status = new ShopStatus();
        $status->model_id = $this->owner->id;
        $status->status_number = $this->owner->is_blacklist;
        $status->model_name = $this->model_name;
        $status->status_type = 2;
        $status->created_at = time();
        $status->cause = $this->owner->cause;
        return $status->save();
    }
    /**
     * 最后一个黑名单对象
     */
    public function getLastJoinBlackList()
    {
        $model = ShopStatus::find()->select(['id','cause', 'created_at', 'status_number'])
        ->where([
            'model_id'=>$this->owner->id,
            'model_name'=>$this->model_name,
            'status_type'=>2
        ])->orderBy('created_at DESC')->one();
        return empty($model)?new ShopStatus():$model;
    }
    /**
     * 最后审核记录
     */
    public function getLastAuditStatus()
    {
        $model = ShopStatus::find()
        ->select(['id','cause', 'created_at', 'status_number'])
        ->where([
            'model_id'=>$this->owner->id,
            'model_name'=>$this->model_name,
            'status_type'=>1
        ])->orderBy('created_at DESC')->one();
        return empty($model)?new ShopStatus():$model;
    }
    /**
     * 最后一个黑名单原因
     */
    public function getLastJoinBlackListCause()
    {
        $model = $this->getLastJoinBlackList();
        return $model->cause;
    }
    /**
     * 最后一个黑名单时间
     */
    public function getLastJoinBlackListTime()
    {
        $model = $this->getLastJoinBlackList();
        return date('Y-m-d H:i:s', (int)$model->created_at);
    }
}