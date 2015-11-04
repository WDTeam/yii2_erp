<?php

namespace boss\controllers\finance;

use core\models\shop\Shop;
use core\models\shop\ShopManager;
use core\models\finance\FinanceSettleApplySearch;
use core\models\finance\FinanceShopSettleApplySearch;
use core\models\finance\FinanceWorkerOrderIncomeSearch;

use PHPExcel;
use PHPExcel_IOFactory;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * FinanceShopSettleApplyController implements the CRUD actions for FinanceShopSettleApply model.
 */
class FinanceShopSettleApplyController extends Controller
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
     * 门店结算列表
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceShopSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->review_section = $requestParams['review_section'];
        if($searchModel->review_section == FinanceShopSettleApplySearch::BUSINESS_REVIEW){
            $searchModel->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT;
        }elseif($searchModel->review_section == FinanceShopSettleApplySearch::FINANCE_REVIEW){
            $searchModel->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED;
        }
        $searchModel->load($requestParams);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionReviewFailedReason(){
        $searchModel = new FinanceShopSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->review_section = $requestParams['review_section'];
        $searchModel->id = $requestParams['id'];
        return $this->renderAjax('reviewFailedReason', [
            'model' => $searchModel,
        ]);
    }
    
    /**
     * 记录审核结果
     * @param type $id
     * @return type
     */
    public function actionReview($id,$review_section,$is_ok){
        $model = $this->findModel($id);
        $requestParams = Yii::$app->request->getQueryParams();
        if(isset($requestParams['comment'])){
            $model->comment = $requestParams['comment'];
        }
        $isFinacePayedConfirm = false;
        if(isset($requestParams['isFinacePayedConfirm'])){
            $isFinacePayedConfirm = $requestParams['isFinacePayedConfirm'];
        }
        if($review_section== FinanceShopSettleApplySearch::BUSINESS_REVIEW){
            if($is_ok == 1){
                $model->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED;
            }else{
                $model->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED;
            }
        }elseif($review_section == FinanceShopSettleApplySearch::FINANCE_REVIEW){
            if($is_ok == 1){
                $model->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED;
                if($isFinacePayedConfirm == true){
                    $model->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED;
                }
            }else{
                $model->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED;
            }
        }
        $model->save();
        return $this->redirect('index?review_section='.$review_section);
    }
    
    /**
     * 门店人工结算信息
     * @return type
     */
    public function actionShopManualSettlementIndex()
    {
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = new FinanceShopSettleApplySearch;
        $searchModel->review_section = Yii::$app->request->getQueryParams()['review_section'];
        $searchModel->load($requestParams);
        $shopModel = new Shop();
        if(!empty($searchModel->shop_id)){
            $shopModel = Shop::findById($searchModel->shop_id);
        }
        $shopManagerModel = new ShopManager();
        $shopManagerModel = ShopManager::findById($searchModel->shop_manager_id);
        if(count($shopManagerModel) > 0){
            $searchModel->shop_manager_name = $shopManagerModel->name;
        }
        $searchModel->getShopSettleInfo($searchModel->shop_id);
        $financeSettleApplySearchModel = new FinanceSettleApplySearch;
        
        $financeSettleApplySearchModel->shop_id = $searchModel->shop_id;
        $financeSettleApplyDataProvider = $financeSettleApplySearchModel->search($requestParams);
        return $this->render('shopManualSettlementIndex', [
            'financeSettleApplyDataProvider' => $financeSettleApplyDataProvider,
            'model' => $searchModel,
            'shopModel' => $shopModel,
        ]);
    }
    
    /**
     * 门店人工结算完成
     * @return type
     */
    public function actionShopManualSettlementDone()
    {
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = new FinanceShopSettleApplySearch;
        $searchModel->load($requestParams);
        if(!empty($searchModel->shop_id)){
            $this->saveAndGenerateSettleData([['shop_id'=>$searchModel->shop_id,'shop_manager_id'=>$searchModel->shop_manager_id],], '', '');
        }
        return $this->redirect('index?review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW);
    }
    
    
    /**
     * 门店结算列表
     * @return mixed
     */
    public function actionQuery()
    {
        $searchModel = new FinanceShopSettleApplySearch;
        $searchModel->scenario = 'query';
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->load($requestParams);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('query', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionExport(){
        $searchModel = new FinanceShopSettleApplySearch;
        $data = $searchModel->find()->all();
        $objPHPExcel=new PHPExcel();
        ob_start();
        $objPHPExcel->getProperties()->setCreator('ejiajie')
                ->setLastModifiedBy('ejiajie')
                ->setTitle('Office 2007 XLSX Document')
                ->setSubject('Office 2007 XLSX Document')
                ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Result file');
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','门店名称')
                    ->setCellValue('B1','归属家政名称')
                    ->setCellValue('C1','完成总单量')
                    ->setCellValue('D1','每单管理费')
                    ->setCellValue('E1','管理费');
           $i=2;   
           foreach($data as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('A'.$i,$v['shop_name'])
                       ->setCellValue('B'.$i,$v['shop_manager_name'])
                       ->setCellValue('C'.$i,$v['finance_shop_settle_apply_order_count'])
                       ->setCellValue('D'.$i,$v['finance_shop_settle_apply_fee_per_order'])
                       ->setCellValue('E'.$i,$v['finance_shop_settle_apply_fee']);
            $i++;
           }
           $objPHPExcel->getActiveSheet()->setTitle('结算');
           $objPHPExcel->setActiveSheetIndex(0);
           $filename=urlencode('门店结算').'_'.date('Y-m-dHis');
           $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
           ob_end_clean();
           header('Content-Type: application/vnd.ms-excel');
           header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        exit;
    }

    /**
     * Displays a single FinanceShopSettleApply model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $orderIncomeSearchModel = new FinanceWorkerOrderIncomeSearch;
        $orderIncomeSearchModel->finance_settle_apply_id = $id;
        $searchModel = new FinanceShopSettleApplySearch;
        $searchModel = $searchModel->find()->where(['id'=>$id])->one();
        $shopModel = Shop::findById($searchModel->shop_id);
        $financeSettleApplySearchModel = new FinanceSettleApplySearch;
        $financeSettleApplySearchModel->shop_id = $searchModel->shop_id;
        $financeSettleApplyDataProvider = $financeSettleApplySearchModel->search(null);
        return $this->render('view', [
             'financeSettleApplyDataProvider' => $financeSettleApplyDataProvider,
            'model' => $searchModel,
            'shopModel' => $shopModel,
        ]);
    }

    /**
     * Creates a new FinanceShopSettleApply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceShopSettleApplySearch;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceShopSettleApply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceShopSettleApply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceShopSettleApply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceShopSettleApply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceShopSettleApplySearch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * 本文件是用于门店每周（例如：2015.9.21-2015.9.27）的结算
    * 1.获取所有小家政门店的信息
    * 2.用每个小家政门店的Id从阿姨结算表中获取小家政服务费
    * 3.保存小家政的结算信息到数据库表
    */
    public function actionShopCycleSettlement(){
        $settleStartTime = date('Y-m-d 00:00:00', strtotime('-1 week last monday'));;//统计开始时间,上周第一天
        echo $settleStartTime.'------';
        $settleEndTime = date('Y-m-d 23:59:59', strtotime('last sunday'));//统计结束时间,上周最后一天
        echo $settleEndTime.'------';
        //获取阿姨的数组信息
        $shopArr = Shop::getShopIds();
        $this->saveAndGenerateSettleData($shopArr,$settleStartTime,$settleEndTime);
    }
    
    private function saveAndGenerateSettleData($shopArr,$settleStartTime,$settleEndTime){
        foreach($shopArr as $shop){
            $searchModel = new FinanceShopSettleApplySearch;
            $shopModel = Shop::findById($shop['shop_id']);
            $shopManagerModel = ShopManager::findById($shop['shop_manager_id']);
            $searchModel->shop_name = $shopModel->name;
            $searchModel->shop_manager_name = $shopManagerModel->name;
            $searchModel->shop_id = $shop['shop_id'];
            $searchModel->shop_manager_id = $shop['shop_manager_id'];
            $searchModel->getShopSettleInfo($shop['shop_id']);
            $searchModel->finance_shop_settle_apply_fee_per_order = FinanceShopSettleApplySearch::MANAGE_FEE_PER_ORDER;
            $searchModel->finance_shop_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->finance_shop_settle_apply_cycle = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_CYCLE_WEEK;
            $searchModel->finance_shop_settle_apply_cycle_des = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_CYCLE_WEEK_DES;
            $searchModel->finance_shop_settle_apply_starttime = time();
            $searchModel->finance_shop_settle_apply_endtime = time();
            $searchModel->created_at = time();
//            var_dump($searchModel);die;
            $searchModel->save();
        }
    }
}
