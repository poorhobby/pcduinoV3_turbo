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
 
echo $_SERVER["argc"];
echo "\n";
echo $_SERVER["argv"][0];
echo "\n";

$config_file = $_SERVER["argv"][1];
 
$config = file_get_contents($config_file);
$setting = json_decode($config);

$gen_setting_path = "/usr/settings";

/*
 * WIFI part.
 */
if (isset($setting->wifi)) {
	echo "wifi settings.\n";
	$hostapd_conf_path=$gen_setting_path.'/hostapd.conf';
	echo $hostapd_conf_path;
	echo "\n";
	
	$hostapd_conf = fopen($hostapd_conf_path, 'w');
	
	fputs($hostapd_conf, "# Basic configuration\n");
	fputs($hostapd_conf, "interface=wlan5\n");
	if (isset($setting->wifi->ssid)) {
		echo "set ssid = ".$setting->wifi->ssid."\n";
		fputs($hostapd_conf, "ssid=".$setting->wifi->ssid."\n");
	} else {
		echo "set ssid = wifi\n";
		fputs($hostapd_conf, "ssid=wifi\n");
	}
	if (isset($setting->wifi->channel)) {
		fputs($hostapd_conf, "channel=".$setting->wifi->channel."\n");
	} else {
		fputs($hostapd_conf, "channel=1\n");
	}
	fputs($hostapd_conf, "# WPA and WPA2 configuration\n");
	fputs($hostapd_conf, "macaddr_acl=0\n");
	fputs($hostapd_conf, "auth_algs=1\n");
	fputs($hostapd_conf, "ignore_broadcast_ssid=0\n");
	if (isset($setting->wifi->security_type)) {
		if ($setting->wifi->security_type == 1) {
			fputs($hostapd_conf, "wpa=1\n");
		} elseif ($setting->wifi->security_type == 2) {
			fputs($hostapd_conf, "wpa=2\n");
		} else {
			fputs($hostapd_conf, "wpa=2\n");
		}
	} else {
		fputs($hostapd_conf, "wpa=2\n");
	}
	
	if(isset($setting->wifi->psk_key)) {
		fputs($hostapd_conf, "wpa_passphrase=".$setting->wifi->psk_key."\n");
	} else {
		fputs($hostapd_conf, "wpa_passphrase=12345678\n");
	}
	
	fputs($hostapd_conf, "wpa_key_mgmt=WPA-PSK\n");
	fputs($hostapd_conf, "wpa_pairwise=TKIP\n");
	fputs($hostapd_conf, "rsn_pairwise=CCMP\n");
	fputs($hostapd_conf, "# Hardware configuration\n");
	fputs($hostapd_conf, "driver=rtl871xdrv\n");
	fputs($hostapd_conf, "ieee80211n=1\n");
	fputs($hostapd_conf, "hw_mode=g\n");
	fputs($hostapd_conf, "device_name=RTL8192CU\n");
	fputs($hostapd_conf, "manufacturer=Realtek\n");
	fputs($hostapd_conf, "\n");
	fclose($hostapd_conf);
}

if (isset($setting->wan)) {
	echo "wan settings.\n";
	$interfaces_conf_path=$gen_setting_path.'/interfaces';
	switch ($setting->wan->wan_type) {
		case '0':
			// static ip
			$interfaces_conf = fopen($interfaces_conf_path, 'w');
			fputs($interfaces_conf, "# interfaces(5) file used by ifup(8) and ifdown(8)\n");
			fputs($interfaces_conf, "auto lo\n");
			fputs($interfaces_conf, "iface lo inet loopback\n");
			fputs($interfaces_conf, "\n");
			fputs($interfaces_conf, "auto wlan5\n");
			fputs($interfaces_conf, "iface wlan5 inet static\n");
			fputs($interfaces_conf, "\taddress ".$setting->lan->lan_address."\n");
			fputs($interfaces_conf, "\tnetmask ".$setting->lan->lan_netmask."\n");
			fputs($interfaces_conf, "\n");
			fclose($interfaces_conf);
			
			//todo gateway
			
			//todo dns
			break;
		case '1':
			//DHCP
			break;
		case '2':
			//pppoe
			break;
		default:
			echo "no this kind of wan type.\n";
			break;
	}
}
if (isset($setting->lan)) {
	$dhcp_conf_path=$gen_setting_path.'/dncpd.conf';
	$dhcp_conf = fopen($dhcp_conf_path, 'w');
	fputs($dhcp_conf, "subnet 192.168.100.0 netmask 255.255.255.0\n");
	fputs($dhcp_conf, "{\n");
		fputs($dhcp_conf, "\trange 192.168.100.10 192.168.100.100;\n");
		fputs($dhcp_conf, "\toption routers ".$setting->lan->lan_address.";\n");
		fputs($dhcp_conf, "\tdomain-name-servers 218.2.135.1;\n");
	fputs($dhcp_conf, "}\n");
	fputs($dhcp_conf, "\n");
	fclose($dhcp_conf);
}


