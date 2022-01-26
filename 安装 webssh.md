工程地址：https://github.com/huashengdun/webssh

安装webssh
--------

```
pip3 install webssh
```

以服务的方式运行
---------------

```
wssh --address='127.0.0.1' --port=8080 --sslport=8443 --certfile='cert.crt' --keyfile='cert.key' --xheaders=False
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
ExecStart=/usr/local/bin/wssh --address='127.0.0.1' --port=8080  --sslport=8443 --certfile='cert.crt' --keyfile='cert.key' --hostfile='/home/darkstar/.ssh/known_hosts' --xheaders=False --policy=reject
Restart=on-failure

[Install]
WantedBy=multi-user.target
```

加上 `--policy=reject` 参数后，只有 known_hosts 保存的主机才允许通过 Webssh 的页面连接。当 known_hosts 更改后，需要运行 `systemctl restart wssh.service` 以便重新读取 known_hosts 文件。

配置为网站的子目录
-----------------

nginx的配置文件中添加

```
location /wssh/ {
    proxy_pass http://127.0.0.1:8888/;
    proxy_http_version 1.1;
    proxy_read_timeout 300;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $http_host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Real-PORT $remote_port;
}
```

启动webssh的命令

```
wssh --address='127.0.0.1' --port=8888 --xheaders=False
```

在地址栏里输入 `https://www.example.com/wssh`即可。


URL 参数
--------

在 URL 中输入主机、用户名及密码 (密码必须用 base64 编码， 不支持 privatekey )
```bash
http://localhost:8888/?hostname=xx&username=yy&password=str_base64_encoded
```

用户自定义网页 title
```bash
http://localhost:8888/?title=my-ssh-server
```

字符编码
```bash
http://localhost:8888/#encoding=gbk
```
