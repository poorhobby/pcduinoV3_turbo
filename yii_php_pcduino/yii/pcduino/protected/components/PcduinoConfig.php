<?php
    class PcduinoConfig extends CModule
    {
    	private $config_path_base = '/home/paul/github/pcduinoV3_turbo/yii_php_pcduino/yii/pcduino';
    	public $wan_config;
		public $lan_config;
		public $wifi_config;
		
		public function __construct()
		{
			$this->wan_config = $this->config_path_base.'/modules/pcduinoSettings/wan.cnf';
			$this->lan_config = $this->config_path_base.'/modules/pcduinoSettings/lan.cnf';
			$this->wifi_config = $this->config_path_base.'/modules/pcduinoSettings/wifi.cnf';
		}
		public function getWanConfig()
		{
			$wan = array('wan_type'=>'StaticIP', 
				'ip_address'=>'10.0.0.1', 
				'ip_netmask'=>'255.0.0.0',
				'pppoe_username'=>'',
				'pppoe_password'=>'',
				);
			$fp = fopen($this->wan_config, "r"); 
			while(! feof($fp)) 
			{
				$string = fgets($fp);
				$key = strtok($string, '=');
				//Yii::log($key, CLogger::LEVEL_ERROR);
				//Yii::log(array_key_exists('ssid', $wifi), CLogger::LEVEL_ERROR);
				if (is_array($key) && array_key_exists($key, $wan)) {
					$value = strtok('=');
					$wan[$key] = $value;
				}
			}
			fclose($fp);
			return $wan;
		}
		
		public function setWanConfig($Settings)
		{
			$wan = array('wan_type', 'ip_address', 'ip_netmask', 'pppoe_username', 'pppoe_password');
			$fp = fopen($this->wan_config, "w");
			$i = 0; 
			//array$wan
			while($i < sizeof($wan))
			{
				if (array_key_exists($wan[$i], $Settings))
				{
					$string = $wan[$i].'='.$Settings[$wan[$i]];
					fputs($fp, $string);
					fputs($fp, "\n");
				}
				$i = $i + 1;
			}
			fclose($fp);
			$this->applyConfig('wan');
		}
		
		public function getLanConfig()
		{
			$lan = array('ip_address'=>'192.168.1.1', 'ip_netmask'=>'255.255.255.0');
			$fp = fopen($this->lan_config, "r"); 
			while(! feof($fp)) 
			{
				$string = fgets($fp);
				$key = strtok($string, '=');
				//Yii::log($key, CLogger::LEVEL_ERROR);
				//Yii::log(array_key_exists('ssid', $wifi), CLogger::LEVEL_ERROR);
				if (is_array($key) && array_key_exists($key, $lan)) {
					$value = strtok('=');
					$lan[$key] = $value;
				}
			}
			fclose($fp);
			return $lan;
		}
		
		public function setLanConfig($Settings)
		{
			$lan = array('wan_type', 'ip_address', 'ip_netmask', 'pppoe_username', 'pppoe_password');
			$fp = fopen($this->lan_config, "w");
			$i = 0; 
			//array$wan
			while($i < sizeof($lan))
			{
				if (array_key_exists($lan[$i], $Settings))
				{
					$string = $lan[$i].'='.$Settings[$lan[$i]];
					fputs($fp, $string);
					fputs($fp, "\n");
				}
				$i = $i + 1;
			}
			fclose($fp);
			$this->applyConfig('lan');
		}
		
		public function getWifiConfig()
		{
			$wifi = array('ssid'=>'pcduino', 'channel'=>'1','security_type'=>'WPA2', 'psk_key'=>'pcduino_key');
			$fp = fopen($this->wifi_config, "r"); 
			while(! feof($fp)) 
			{
				$string = fgets($fp);
				$key = strtok($string, '=');
				//Yii::log($key, CLogger::LEVEL_ERROR);
				//Yii::log(array_key_exists('ssid', $wifi), CLogger::LEVEL_ERROR);
				if (is_array($key) && array_key_exists($key, $wifi)) {
					$value = strtok('=');
					$wifi[$key] = $value;
				}
			}
			fclose($fp);
			return $wifi;
		}
		
		public function setWifiConfig($Settings)
		{
			$config = json_encode($Settings);
			
			$wifi = array('ssid', 'channel', 'security_type', 'psk_key');
			$fp = fopen($this->wifi_config, "w");
			/*$i = 0; 
			//array$wan
			while($i < sizeof($wifi))
			{
				if (array_key_exists($wifi[$i], $Settings))
				{
					$string = $wifi[$i].'='.$Settings[$wifi[$i]];
					fputs($fp, $string);
					fputs($fp, "\n");
				}
				$i = $i + 1;
			}*/
			fputs($fp, $config);
			fclose($fp);
			
			// use pcduino_cli sending msg to pcduino_daemon
			// is_executable($filename)
			//$this->applyConfig('wifi');
			
		}
		public function applyConfig($category)
		{
			$cliPath = '/home/paul/workspace/pcduino_cli/Debug/pcduino_cli';
			switch ($category) {
				case 'wifi':
					$result = exec($cliPath.' -i '.$this->wifi_config);
					break;
				case 'wan':
					$result = exec($cliPath.' -w '.$this->wan_config);
					break;
				case 'lan':
					Yii::log('lan config', CLogger::LEVEL_ERROR);
					$result = exec($cliPath.' -l '.$this->lan_config);
					Yii::log($result, CLogger::LEVEL_ERROR);
					break;
				default:
					//command error.
					break;
			}
		}
    }

