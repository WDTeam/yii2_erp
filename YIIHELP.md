# 使用要点
# 数据库使用场景
1: $dataProvider 排序 ,目前的yii search方法中使用
  
    public function actionIndex()
    {
        $searchModel = new FinanceOrderChannelSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->query->orderBy(['id' => SORT_DESC ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
        
        
    }