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
$cs->registerScriptFile(Yii::app()->baseUrl . '/modules/assets/wanSetting/wanSetting.js', CClientScript::POS_HEAD);

?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pcduino-wan-setting-pcduinoWanSetting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'wan_type'); ?>
		<?php echo $form->dropDownList($model, 'wan_type', array(0=>'StaticIP', 1=>'DHCP', 2=>'PPPoE'), array('onchange'=>'wan_type_change()')); ?>
		<!--?php echo $form->textField($model,'wan_type'); ?-->
		<!--?php echo $form->error($model,'wan_type'); ?-->
	</div>

	<div class="StaticIp">
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
	</div>
	
	<div class="DHCP">
		<div class="row">
			<!-- nothing -->
			<!--TODO: display port ip information-->
			<p>@TODO Supporting DHCP</p>
		</div>
	</div>
	<div class="PPPoE">
			<!-- nothing -->
			<!--TODO: display port ip information-->
		<div class="row">
			<?php echo $form->labelEx($model,'pppoe_username'); ?>
			<?php echo $form->textField($model,'pppoe_username'); ?>
			<?php echo $form->error($model,'pppoe_username'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'pppoe_password'); ?>
			<?php echo $form->textField($model,'pppoe_password'); ?>
			<?php echo $form->error($model,'pppoe_password'); ?>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->