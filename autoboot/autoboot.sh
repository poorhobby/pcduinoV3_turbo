# Write this file's content to /etc/rc.local

# poll up hostapd.
/usr/local/bin/hostapd -B /etc/hostapd/hostapd.conf

# restart dhcpd service.
service isc-dhcp-server restart

# open ip forward.
echo 1 > /proc/sys/net/ipv4/ip_forward

# open nat.
/sbin/iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE


