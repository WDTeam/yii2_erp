<?php
use yii\helpers\Url;

$search_class_name = 'OrderSearch';
$search_filed_name = $filter_name;
$toUrl = Url::to(['']).'?&i=1';

$para_current_value = null;
$params = Yii::$app->request->getQueryParams();
if (isset($params[$search_class_name]))
{
    foreach ($params[$search_class_name] as $key => $value)
    {
        if ($key != $search_filed_name)
            $toUrl = $toUrl.'&'.$search_class_name.'['.$key.']'.'='.$value;
        else
            $para_current_value = $value;
    }
}

if ($para_current_value == 0)
    echo '<li class="cur"><a href="'.$toUrl.'">全部</a></li>';
else
    echo '<li><a href="'.$toUrl.'">全部</a></li>';

foreach ($items as $key => $value)
{
    if ($para_current_value == $key)
        echo '<li class="cur"><a href="'.$toUrl.'&'.$search_class_name.'['.$search_filed_name.']'.'='.$key.'">'.$value.'</a></li>';
    else
        echo '<li><a href="'.$toUrl.'&'.$search_class_name.'['.$search_filed_name.']'.'='.$key.'">'.$value.'</a></li>';
}
?>