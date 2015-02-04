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
	'id'=>'pcduino-wifi-setting-pcduinoWifiSetting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="line" id="wlanBasic">
		<div class="row">
			<?php echo $form->labelEx($model,'ssid'); ?>
			<?php echo $form->textField($model,'ssid'); ?>
			<?php echo $form->error($model,'ssid'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'channel'); ?>
			<?php echo $form->textField($model,'channel'); ?>
			<?php echo $form->error($model,'channel'); ?>
		</div>
	</div>
	
	<div class="line" id="wlanSecurity">
		
		<div class="row">
			<?php echo $form->labelEx($model,'security_type'); ?>
			<?php echo $form->dropDownList($model, 'security_type', array(0=>'WPA', 1=>'WPA2')); ?>
			<!--?php echo $form->textField($model,'wan_type'); ?-->
			<!--?php echo $form->error($model,'wan_type'); ?-->
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'psk_key'); ?>
			<?php echo $form->textField($model,'psk_key'); ?>
			<?php echo $form->error($model,'psk_key'); ?>
		</div>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->