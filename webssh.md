
安装webssh
--------

```
pip3 install webssh
```

以服务的方式运行
---------------

```
wssh --port=8080 --sslport=4433 --certfile='cert.crt' --keyfile='cert.key' --xheaders=False --policy=reject
```

 --policy=reject 
 Try to use reject policy as the missing host key policy along with your verified known_hosts, this will prevent man-in-the-middle attacks. The idea is that it checks the system host keys file("~/.ssh/known_hosts") and the application host keys file("./known_hosts") in order, if the ssh server's hostname is not found or the key is not matched, the connection will be aborted.
 
配置自启动
----------

```
[Unit]
Description=Webssh Service
Documentation=Webssh debian service 
After=network.target nss-lookup.target

[Service]
# If the version of systemd is 240 or above, then uncommenting Type=exec and commenting out Type=simple
#Type=exec
Type=simple
User=root
#User=nobody
NoNewPrivileges=true
ExecStart=/usr/local/bin/wssh --address='127.0.0.1' --xheaders=False
Restart=on-failure

[Install]
WantedBy=multi-user.target
```
