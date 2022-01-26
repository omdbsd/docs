tunnelbroker, Create Regular Tunnel, 选一个ping值最低的 server。 在example configurations里选择 linux-route2，把其中的 local 后的地址改成用 ifconfig 获得的内网地址，由此得到命令集。

新建一个shell脚本文件，将命令集拷贝到里面，并在最前面添加一行#!/bin/sh 。运行该脚本即可获得ipv6。

以上之所以不选debian/ubuntu，而选择linux-route2，是因为自ubuntu 18之后，已经不再使用/etc/network/interfaces，而是使用/etc/netplan。

维持隧道连接
------------

如果一段时间没有流量，隧道会进入休眠状态，从外部也就无法访问了。因此我们可以主动访问一下IPv6网络以维持隧道活动，比如ping -c5 ipv6.google.com，设置10分钟左右执行一次即可，用crontab就能够很简单的实现。

此外有一点需要注意，HE Tunnel的IPv6无法作为CloudFlare的源站IP，因为CloudFlare并不认为HE的隧道是有效的真实IP，所以只能作为一种访问IPv6网络的方式。
