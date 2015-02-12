<?php
/* @var $this PcduinoWanSettingController */
/* @var $model PcduinoWanSetting */
/* @var $form CActiveForm */
?>

<?php
//$cs=Yii::app()->clientScript;
// this will cause php to include the jquery.js to <header>
Yii::app()->clientScript->registerCoreScript('jquery');


$cs=Yii::app()->clientScript;
// this will include pcduino/xxx.js.
$cs->registerScriptFile(Yii::app()->baseUrl . '/modules/assets/wanSetting.js', CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->baseUrl.'/modules/assets/setting.css');
$cs->registerCssFile(Yii::app()->baseUrl.'/modules/assets/download.css');
?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pcduino-wifi-setting-PcduinoWget-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<label class="labelclass">WGet download</label>
	<div class="divSettings">
		<div class="row">
			<?php echo $form->labelEx($model,'url'); ?>
			<?php echo $form->textArea($model,'url'); ?>
			<?php echo $form->error($model,'url'); ?>
		</div>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->