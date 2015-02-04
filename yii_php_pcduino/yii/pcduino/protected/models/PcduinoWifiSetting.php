<?php

/**
 * This is the model class for table "pcduino_wansetting".
 *
 * The followings are the available columns in table 'pcduino_wansetting':
 * @property string $wan_type
 * @property string $ip_address
 * @property string $ip_netmask
 */
class PcduinoWifiSetting extends CFormModel
{
	public $ssid;
	public $channel;
	public $security_type;
	public $psk_key;
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
		return 'pcduino_lansetting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ssid, channel, security_type, psk_key', 'safe'),
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
			'ssid' => 'SSID',
			'channel' => 'Channel',
			'security_type' => 'Security',
			'psk_key' => 'Key',
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
		// $criteria->compare('ip_address',$this->ip_address,true);
		// $criteria->compare('ip_netmask',$this->ip_netmask,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function applySetting()
	{
		$security_list = array(0=>'WPA', 1=>'WPA2');
		$wanSettings = new PcduinoConfiguration;
		$setting = array();
		$setting['ssid'] = $this->ssid;
		$setting['channel'] = $this->channel;
		$setting['security_type'] = $security_list[$this->security_type];
		$setting['psk_key'] = $this->psk_key;
		$wanSettings->setWifiConfiguration($setting);
	}
	
	public function gatherSetting()
	{
		$security_list = array('WPA'=>0, 'WPA2'=>1);
		$wanSettings = new PcduinoConfiguration;
		$settings = $wanSettings->getWifiConfiguration();
		$this->ssid = $settings['ssid'];
		$this->channel = $settings['channel'];
		$this->security_type = $security_list[$settings['security_type']];
		$this->psk_key = $settings['psk_key'];
	}
}