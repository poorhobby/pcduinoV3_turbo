<?php

/**
 * This is the model class for table "pcduino_wansetting".
 *
 * The followings are the available columns in table 'pcduino_wansetting':
 * @property string $wan_type
 * @property string $ip_address
 * @property string $ip_netmask
 */
class PcduinoLanSetting extends CFormModel
{
	public $ip_address;
	public $ip_netmask;
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
			array('ip_address, ip_netmask', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ip_address, ip_netmask', 'safe'),
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
			'ip_address' => 'Ip Address',
			'ip_netmask' => 'Ip Netmask',
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
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('ip_netmask',$this->ip_netmask,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function applySetting()
	{
		$wanSettings = Yii::app()->PcduinoConfig;
		$setting = array();
		$setting['ip_address'] = $this->ip_address;
		$setting['ip_netmask'] = $this->ip_netmask;
		$wanSettings->setLanConfig($setting);
	}
	
	public function gatherSetting()
	{
		$wanSettings = Yii::app()->PcduinoConfig;
		$settings = $wanSettings->getLanConfig();
		$this->ip_address = $settings['ip_address'];
		$this->ip_netmask = $settings['ip_netmask'];
	}
}