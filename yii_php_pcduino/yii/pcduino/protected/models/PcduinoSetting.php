<?php

/**
 * This is the model class for table "pcduino_wansetting".
 *
 * The followings are the available columns in table 'pcduino_wansetting':
 * @property string $wan_type
 * @property string $ip_address
 * @property string $ip_netmask
 */
class PcduinoSetting extends CFormModel
{
	/**
	 * Wan setting variables.
	 */
	public $wan_type;
	public $wan_address;
	public $wan_netmask;
	public $wan_gateway; 
	public $wan_pridns;
	public $wan_secdns;
	public $pppoe_username;
	public $pppoe_password;
	
	public $lan_address;
	public $lan_netmask;
	public $dhcp_ip_start;
	public $dhcp_ip_end;
	// public $dhcp_ip_gateway;
	
	public $ssid;
	public $channel;
	public $security_type;
	public $psk_key;
	
	private $settings;
	private $config_path_base = '/usr/settings';
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
			array('wan_type, wan_address, 
				wan_netmask, wan_gateway, 
				wan_pridns, wan_secdns,pppoe_username, 
				pppoe_password, lan_address, 
				lan_netmask, dhcp_ip_start, dhcp_ip_end, ssid, channel, 
				security_type, psk_key',
				'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lan_address' => 'LAN Address',
			'lan_netmask' => 'LAN Netmask',
			'dhcp_ip_start' => 'DHCP Address Start',
			'dhcp_ip_end' => 'DHCP Address End',
			'wan_type' => 'WAN Type',
			'wan_address' => 'WAN Address',
			'wan_netmask' => 'WAN Netmask',
			'wan_gateway' => 'WAN Gateway', 
			'wan_pridns' => 'WAN Primary DNS',
			'wan_secdns' => 'WAN Secondary DNS',
			'pppoe_username' => 'User Name',
			'pppoe_password' => 'Password',
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

	private function genJsonData()
	{
		$this->settings['wan']['wan_type'] = $this->wan_type;
		$this->settings['wan']['wan_address'] = $this->wan_address;
		$this->settings['wan']['wan_netmask'] = $this->wan_netmask;
		$this->settings['wan']['wan_gateway'] = $this->wan_gateway;
		$this->settings['wan']['wan_pridns'] = $this->wan_pridns;
		$this->settings['wan']['wan_secdns'] = $this->wan_secdns;
		$this->settings['wan']['pppoe_username'] = $this->pppoe_username;
		$this->settings['wan']['pppoe_password'] = $this->pppoe_password;
		
		$this->settings['lan']['lan_address'] = $this->lan_address;
		$this->settings['lan']['lan_netmask'] = $this->lan_netmask;
		$this->settings['lan']['dhcp_ip_start'] = $this->dhcp_ip_start;
		$this->settings['lan']['dhcp_ip_end'] = $this->dhcp_ip_end;
		
		$this->settings['wifi']['ssid'] = $this->ssid;
		$this->settings['wifi']['channel'] = $this->channel;
		$this->settings['wifi']['security_type'] = $this->security_type;
		$this->settings['wifi']['psk_key'] = $this->psk_key;
	}
	
	private function parseJsonData()
	{
		// $xmlfile = 'test.xml';
// 
		// $xmlparser = xml_parser_create();
// 		
		// // 打开文件并读取数据
		// $fp = fopen($xmlfile, 'r');
		// while ($xmldata = fread($fp, 4096)) 
		  // {
		  // // parse the data chunk
		  // if (!xml_parse($xmlparser,$xmldata,feof($fp))) 
		    // {
		    // die( print "ERROR: "
		    // . xml_get_error_code($xmlparser)
		    // . "<br />"
		    // . "Line: "
		    // . xml_get_current_line_number($xmlparser)
		    // . "<br />"
		    // . "Column: "
		    // . xml_get_current_column_number($xmlparser)
		    // . "<br />");
		    // }
		  // }
// 		
		// xml_parser_free($xmlparser);

		$this->wan_type = $this->settings['wan']['wan_type'];
		$this->wan_address = $this->settings['wan']['wan_address'];
		$this->wan_netmask = $this->settings['wan']['wan_netmask'];
		$this->wan_gateway = $this->settings['wan']['wan_gateway'];
		$this->wan_pridns = $this->settings['wan']['wan_pridns'];
		$this->wan_secdns = $this->settings['wan']['wan_secdns'];
		$this->pppoe_username = $this->settings['wan']['pppoe_username'];
		$this->pppoe_password = $this->settings['wan']['pppoe_password'];
		
		$this->lan_address = $this->settings['lan']['lan_address'];
		$this->lan_netmask = $this->settings['lan']['lan_netmask'];
		$this->dhcp_ip_start = $this->settings['lan']['dhcp_ip_start'];
		$this->dhcp_ip_end = $this->settings['lan']['dhcp_ip_end'];
		
		$this->ssid = $this->settings['wifi']['ssid'];
		$this->channel = $this->settings['wifi']['channel'];
		$this->security_type = $this->settings['wifi']['security_type'];
		$this->psk_key = $this->settings['wifi']['psk_key'];
	}

	public function useDefaultSettings()
	{
		$this->wan_type = 0;//static ip
		$this->wan_address = '10.0.0.1';
		$this->wan_netmask = '255.0.0.0';
		$this->wan_gateway = '10.0.0.254';
		$this->wan_pridns = '8.8.8.8';
		$this->wan_secdns = '';
		$this->pppoe_username = 'username';
		$this->pppoe_password = 'password';
		
		$this->lan_address = '192.168.0.1';
		$this->lan_netmask = '255.255.255.0';
		$this->dhcp_ip_start = '192.168.0.100';
		$this->dhcp_ip_end = '192.168.0.200';
		
		$this->ssid = 'pcduino_wifi';
		$this->channel = 1;
		$this->security_type = 1;//wpa2
		$this->psk_key = '88888888';
	}
	
	public function applySetting()
	{
		$config_file = $this->config_path_base.'/pcduino.cnf';
		$this->genJsonData();
		$config = json_encode($this->settings, JSON_FORCE_OBJECT);
		file_put_contents($config_file, $config);
		$result = exec($this->cliCmd.' -s '.$config_file);
	}
	
	public function gatherSetting()
	{
		$config_file = $this->config_path_base.'/pcduino.cnf';
		if (!file_exists($config_file)) {
			$this->useDefaultSettings();
		} else {
			$config = file_get_contents($config_file);
			$this->settings = json_decode($config,TRUE);
			$this->parseJsonData();
		}
	}
}