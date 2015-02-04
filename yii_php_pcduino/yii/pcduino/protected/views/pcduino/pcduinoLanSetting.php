<?php
/* @var $this PcduinoWanSettingController */
/* @var $model PcduinoWanSetting */
/* @var $form CActiveForm */
?>

<?php
//$cs=Yii::app()->clientScript;
// this will cause php to include the jquery.js to <header>
Yii::app()->clientScript->registerCoreScript('jquery');


//$cs=Yii::app()->clientScript;
// this will include pcduino/xxx.js.
//$cs->registerScriptFile(Yii::app()->baseUrl . '/modules/assets/wanSetting/wanSetting.js', CClientScript::POS_HEAD);

?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pcduino-lan-setting-pcduinoLanSetting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_address'); ?>
		<?php echo $form->textField($model,'ip_address'); ?>
		<?php echo $form->error($model,'ip_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_netmask'); ?>
		<?php echo $form->textField($model,'ip_netmask'); ?>
		<?php echo $form->error($model,'ip_netmask'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->