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
?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pcduino-wifi-setting-pcduinoSetting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<label class="labelclass"> WAN Settings</label>
	<div class="divSettings">
		<div class="row">
			<?php echo $form->labelEx($model,'wan_type'); ?>
			<?php echo $form->dropDownList($model, 'wan_type', array(0=>'StaticIP', 1=>'DHCP', 2=>'PPPoE'), array('onchange'=>'wan_type_change()')); ?>
			<!--?php echo $form->textField($model,'wan_type'); ?-->
			<!--?php echo $form->error($model,'wan_type'); ?-->
		</div>
		<div class="StaticIp">
			<div class="row">
				<?php echo $form->labelEx($model,'wan_address'); ?>
				<?php echo $form->textField($model,'wan_address'); ?>
				<?php echo $form->error($model,'wan_address'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'wan_netmask'); ?>
				<?php echo $form->textField($model,'wan_netmask'); ?>
				<?php echo $form->error($model,'wan_netmask'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'wan_gateway'); ?>
				<?php echo $form->textField($model,'wan_gateway'); ?>
				<?php echo $form->error($model,'wan_gateway'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'wan_pridns'); ?>
				<?php echo $form->textField($model,'wan_pridns'); ?>
				<?php echo $form->error($model,'wan_pridns'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'wan_secdns'); ?>
				<?php echo $form->textField($model,'wan_secdns'); ?>
				<?php echo $form->error($model,'wan_secdns'); ?>
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
	</div>
	<br />
	<label class="labelclass"> LAN Settings</label>
	<div class="divSettings">
		<div class="row">
			<?php echo $form->labelEx($model,'lan_address'); ?>
			<?php echo $form->textField($model,'lan_address'); ?>
			<?php echo $form->error($model,'lan_address'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'lan_netmask'); ?>
			<?php echo $form->textField($model,'lan_netmask'); ?>
			<?php echo $form->error($model,'lan_netmask'); ?>
		</div>
		
		<label>DHCP Address Pools</label>
		<div class="row">
			<?php echo $form->labelEx($model,'dhcp_ip_start'); ?>
			<?php echo $form->textField($model,'dhcp_ip_start'); ?>
			<?php echo $form->error($model,'dhcp_ip_start'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'dhcp_ip_end'); ?>
			<?php echo $form->textField($model,'dhcp_ip_end'); ?>
			<?php echo $form->error($model,'dhcp_ip_end'); ?>
		</div>
	</div>
	<br />
	<label class="labelclass"> WIFI Settings</label>
	<div class="divSettings">
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
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->