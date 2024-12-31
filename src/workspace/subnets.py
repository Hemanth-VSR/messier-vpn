from netaddr import IPNetwork
for ip in IPNetwork('172.20.0.2/16'):
	print('%s' % ip)

