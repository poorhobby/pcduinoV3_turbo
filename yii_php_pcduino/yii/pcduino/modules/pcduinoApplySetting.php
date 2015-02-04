<?php
/**
 * # Basic configuration
 * interface=wlan5  #根据你自己的接点
 * ssid=wifi
 * channel=1
 * # bridge=br0
 * # WPA and WPA2 configuration
 * macaddr_acl=0
 * auth_algs=1
 * ignore_broadcast_ssid=0
 * wpa=2
 * wpa_passphrase=12345678
 * wpa_key_mgmt=WPA-PSK
 * wpa_pairwise=TKIP
 * rsn_pairwise=CCMP
 * # Hardware configuration
 * driver=rtl871xdrv
 * ieee80211n=1
 * hw_mode=g
 * device_name=RTL8192CU
 * manufacturer=Realtek
 */
 
$config_path_base = '/home/paul/workspace/yii_php_pcduino/yii/pcduino';
$config_file = $config_path_base.'/modules/pcduinoSettings/pcduino.cnf';
$config = file_get_contents($config_file);
$setting = json_decode($config);
if (isset($setting->wifi)) {
	echo "wifi";
	if (isset($setting->wifi->ssid)) {
		echo "ssid";
	}
}
    
