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
    2:leftjoin多表关联
      ->select('a.id as id,title,create_time,modify_time,username,name')
        ->from('yii_article AS a')
        ->leftJoin('yii_user AS u','u.id = a.user_id')
        ->leftJoin('yii_category AS c','c.id = a.category_id')
        ->where(['c.name'=>'新闻分类'])
        ->limit(4)
        ->orderBy('id DESC')
        ->All();