<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = isset($title)?$title:$courseware->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="study_pre_service_answer">
	<?php $form = ActiveForm::begin(['action' => ['save-service-answer']]); ?>
	<?php foreach($questions as $key=>$question){?>
	    <div class="study_form_rows">
	    	<h3><?php echo $question->title;?></h3>
                <?php if(!$question->is_multi){?>
                    <?php echo Html::radioList("answer_options[{$key}]", YII_ENV=='dev'?$question->correct_options:null, $question->question_shuffle($question->getOptions()));?>
                <?php } else {?>
                    <?php echo Html::checkboxList("answer_options[{$key}]", YII_ENV=='dev'?$question->correct_options:null, $question->question_shuffle($question->getOptions()));?>
                <?php }?>
                <?php echo Html::hiddenInput('answer_ids[]',$question->id);?> 
	    </div>
	<?php }?>
        <?php echo Html::hiddenInput('courseware_id',$courseware->id);?>
	<?php echo Html::submitButton('提交',['class'=>'btn btn-primary']);?>
	<?php ActiveForm::end(); ?>
</div>