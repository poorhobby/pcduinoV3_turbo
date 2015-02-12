<?php

/**
 * This is the model class for table "pcduino_wansetting".
 *
 * The followings are the available columns in table 'pcduino_wansetting':
 * @property string $wan_type
 * @property string $ip_address
 * @property string $ip_netmask
 */
class PcduinoWget extends CFormModel
{
	/**
	 * Wan setting variables.
	 */
	public $url;
	private $cliCmd = '/usr/settings/pcduino_cli';
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('url', 'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'url' => 'URL',
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

	public function download()
	{
		$result = exec($this->cliCmd.' -d '.$this->url);
	}
}