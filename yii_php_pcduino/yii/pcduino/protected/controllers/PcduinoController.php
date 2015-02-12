<?php

class PcduinoController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->getIsGuest()) {
			// if requesting index.php, replace with SetupLogin action. 
			Yii::app()->user->loginRequired();
		}
		$this->actionSetting();
	}
	
	public function actionLogin()
	{
		$model = new PcduinoLoginForm;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['PcduinoLoginForm']))
		{
			$model->attributes=$_POST['PcduinoLoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				//Yii::log(Yii::app()->user->returnUrl, CLogger::LEVEL_ERROR);
				
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('pcduinoLogin',array('model'=>$model));
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	
	public function actionPhpinfo()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('phpinfo');
	}
	
	public function actionSetting()
	{
		$model = new PcduinoSetting;
		if (Yii::app()->user->getIsGuest()) {
			//Yii::app()->user->setReturnUrl();
			Yii::app()->user->loginRequired();
		} else {
			if(isset($_POST['PcduinoSetting'])) {
				$model->gatherSetting();
				$model->lanOrWan($_SERVER['REMOTE_ADDR']);
				$model->attributes=$_POST['PcduinoSetting'];
				//Yii::log($model->pppoe_username, CLogger::LEVEL_ERROR);
				$model->applySetting();
				$this->render('pcduinoWaitSettingApply', array('model'=>$model));
			} else {
				$model->gatherSetting();
				$this->render('pcduinoSetting', array('model'=>$model));
			}
		}
	}
	
	public function actionDownload()
	{
		$model = new PcduinoWget;
		if (Yii::app()->user->getIsGuest()) {
			//Yii::app()->user->setReturnUrl();
			Yii::app()->user->loginRequired();
		} else {
			if(isset($_POST['PcduinoWget'])) {
				$model->attributes=$_POST['PcduinoWget'];
				//Yii::log($model->pppoe_username, CLogger::LEVEL_ERROR);
				$model->download();
				$this->render('pcduinoWget', array('model'=>$model));
			} else {
				$this->render('pcduinoWget', array('model'=>$model));
			}
		}
	}
}
