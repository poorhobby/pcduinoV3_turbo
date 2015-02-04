<?php

/**
 * This is the model class for table "pcduino_wansetting".
 *
 * The followings are the available columns in table 'pcduino_wansetting':
 * @property string $wan_type
 * @property string $ip_address
 * @property string $ip_netmask
 */
class PcduinoWanSetting extends CFormModel
{
	public $wan_type;
	public $ip_address;
	public $ip_netmask;
	public $pppoe_username;
	public $pppoe_password;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PcduinoWanSetting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pcduino_wansetting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wan_type', 'length', 'max'=>1),
			array('ip_address, ip_netmask', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('wan_type, ip_address, ip_netmask, pppoe_username, pppoe_password', 'safe'),
			//array('pppoe_username'),
			//array('pppoe_password'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'wan_type' => 'Wan Type',
			'ip_address' => 'Ip Address',
			'ip_netmask' => 'Ip Netmask',
			'pppoe_username' => 'User Name',
			'pppoe_password' => 'Password',
			);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('wan_type',$this->wan_type,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('ip_netmask',$this->ip_netmask,true);
		$criteria->compare('pppoe_username',$this->pppoe_username,true);
		$criteria->compare('pppoe_password',$this->pppoe_password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function applySetting()
	{
		$wanSettings = new PcduinoConfiguration;
		$setting = array();
		if ($this->wan_type == 0) {
			$setting['wan_type'] = 'StaticIp';
		} elseif($this->wan_type == 1) {
			$setting['wan_type'] = 'DHCP';
		} elseif($this->wan_type == 2) {
			$setting['wan_type'] = 'PPPoE';
		}
		$setting['ip_address'] = $this->ip_address;
		$setting['ip_netmask'] = $this->ip_netmask;
		$setting['pppoe_username'] = $this->pppoe_username;
		$setting['pppoe_password'] = $this->pppoe_password;
		$wanSettings->setWanConfiguration($setting);
	}
	
	public function gatherSetting()
	{
		$wanSettings = new PcduinoConfiguration;
		$settings = $wanSettings->getWanConfiguration();
		//Yii::log($settings['wan_type'], CLogger::LEVEL_ERROR);
		if ($settings['wan_type'] == 'StaticIp') {
			$this->wan_type = 0;
		} elseif ($settings['wan_type'] == 'DHCP') {
			$this->wan_type = 1;
		} elseif($settings['wan_type'] == 'PPPoE') {
			$this->wan_type = 2;
		}
		$this->ip_address = $settings['ip_address'];
		$this->ip_netmask = $settings['ip_netmask'];
		$this->pppoe_username = $settings['pppoe_username'];
		$this->pppoe_password = $settings['pppoe_password'];
	}
}