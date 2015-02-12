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
$cs->registerCssFile(Yii::app()->baseUrl.'/modules/assets/setting.css');
if($model->lan_or_wan) {
	echo $model->lan_or_wan;
	$ip = $model->wan_address;
} else {
	$ip = $model->lan_address;
}

$url = 'http://'.$ip.'/pcduino/index.php?r=pcduino/Setting';
$jumpUrlScript = 'var jumpUrl='."\"".$url."\"";
$cs->registerScript('pcduinoSettingJumpUrl', $jumpUrlScript , CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->baseUrl . '/modules/assets/waitSettingApply.js', CClientScript::POS_HEAD);
?>
	<div class="waiting">
		<p>PCDuino Settings, Please Wait</p>
		<!--br /-->
		<p id="waitTime">5</p>
		<!--br /-->
		<p>Seconds!</p>
	</div>
	<br />

</div><!-- form -->