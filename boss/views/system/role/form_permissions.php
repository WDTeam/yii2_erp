<?php
use kartik\helpers\Html;
?>
<div style="padding-left:20px;">
    <?php
    foreach ($menus as $menu){
        $url = $menu['url'];
        if(isset($menu['can'])){
            echo Html::activeCheckboxList($model, 'permissions', [
                $menu['can']=>$menu['label']
            ],[
                'id'=>'item_'.$menu['can'],
            ]);
        }elseif (isset($url[0]) && $url[0]!='#'){
            echo Html::activeCheckboxList($model, 'permissions', [
                $url[0]=>$menu['label']
            ],[
                'id'=>'item_'.$url[0],
            ]);
        }else{
            echo Html::tag('label', $menu['label'], ['class'=>'control-label']);
        }
        if(isset($menu['items'])){
            echo $this->render('form_permissions', [
    		    'menus'=>$menu['items'],
    		    'model'=>$model,
    		]);
        }
    }
    ?>
</div>