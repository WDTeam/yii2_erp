<?php
namespace boss\behaviors;

use yii\base\Behavior;
use yii\base\InvalidValueException;
use yii\base\Object;
use boss\models\ShopStatus;
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
            self::CHANGE_STATUS=> 'changeStatus',
            self::CHANGE_BLACKLIST=> 'changeBlacklist'
        ];
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
        $last_status = ShopStatus::find()->select(['is_blacklist'])
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
    
    
}