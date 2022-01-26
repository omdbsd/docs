
安装webssh
--------

```
pip3 install webssh
```

以服务的方式运行
---------------

```
wssh --port=8080 --sslport=8443 --certfile='cert.crt' --keyfile='cert.key' --xheaders=False
```
 
配置自启动
----------

编辑 `/lib/systemd/system/wssh.service`

```
[Unit]
Description=Webssh Service
Documentation=Webssh debian service 
After=network.target nss-lookup.target

[Service]
# If the version of systemd is 240 or above, then uncommenting Type=exec and commenting out Type=simple
Type=exec
#Type=simple
User=root
#User=nobody
NoNewPrivileges=true
ExecStart=/usr/local/bin/wssh --port=8080  --sslport=8443 --certfile='cert.crt' --keyfile='cert.key' --hostfile='/home/darkstar/.ssh/known_hosts' --xheaders=False --policy=reject
Restart=on-failure

[Install]
WantedBy=multi-user.target
```

加上 `--policy=reject` 参数后，只有 known_hosts 保存的主机才允许通过 Webssh 的页面连接。当 known_hosts 更改后，需要运行 `systemctl restart wssh.service` 以便重新读取 known_hosts 文件。
