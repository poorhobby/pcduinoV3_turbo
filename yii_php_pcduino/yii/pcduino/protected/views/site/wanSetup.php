<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<p>AAAAAAAAAAAAA</p>
<frameset border="0" framespacing="0" rows="62,*,20" frameborder="no" cols="*">
	<frameset border="0" framespacing="0" rows="62" frameborder="no" cols="155,*">
		<frame name="topLeftFrame" src="./MW305R_files/logo.html"  scrolling="no">
		<frame name="topRightFrame" src="./MW305R_files/banner.html" scrolling="no">
	</frameset>
	<frameset border="0" framespacing="0" rows="*" frameborder="no" cols="155,*">
		<frame name="bottomLeftFrame" src="./MW305R_files/MenuRpm.html" >
		<frame name="mainFrame" src="./MW305R_files/StatusRpm.html" frameborder="no">
	</frameset>
	<frameset border="0" framespacing="0" rows="20" frameborder="no" cols="155,*">
		<frame src="./MW305R_files/bottom1.html"  scrolling="no">
		<frame src="./MW305R_files/bottom2.html"  scrolling="no">
	</frameset>
</frameset>


