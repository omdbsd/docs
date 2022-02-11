

### 安装 shadowsocks-libev 及 v2ray-plugin

shadowsocks-libev 用 C 语言编写，速度快。

```
apt install shadowsocks-libev 
```

下载最新版的 [v2ray-plugin 插件](https://github.com/shadowsocks/v2ray-plugin/releases/latest)，将解压后的插件重命名为 v2ray-plugin 并移到 /usr/bin 目录下

```
mv v2ray-plugin_linux_amd64 /usr/bin/v2ray-plugin
```

更改 shadowsocks-libev 服务器端的配置，编辑 `/etc/shadowsocks-libev/config.json`

```
{
    "server":"localhost",
    "mode":"tcp_only",
    "server_port":23333,
    "local_port":1080,
    "password":"whatever",
    "timeout":300,
    "method":"chacha20-ietf-poly1305",
    "plugin":"v2ray-plugin",
    "plugin_opts":"server;host=127.0.0.1;path=/ray"
}
```

### 安装 nginx 并进行端口转发

安装 nginx 并用 acme-tiny 申请证书（详细过程请参考以往文章）。

nginx 的配置如下

```
   server {
        listen 443 ssl http2 default_server;
        listen [::]:443 ssl http2 default_server;
        ssl_certificate       /ssl/example.com/signed_chain.crt;
        ssl_certificate_key   /ssl/example.com/domain.key;
        ssl_protocols         TLSv1 TLSv1.1 TLSv1.2;
        ssl_ciphers           HIGH:!aNULL:!MD5;
        server_name           example.com;
        index index.html index.htm index.nginx-debian.html;
        root  /var/www/html;
        error_page 400 = /400.html;

        location /ray {
            proxy_pass http://127.0.0.1:23333;
            proxy_redirect      off;
            proxy_http_version  1.1;
            proxy_set_header    Host $http_host;
            proxy_set_header    Upgrade $http_upgrade;
            proxy_set_header    Connection "upgrade";
          
        }
   }   
```

### 启动服务器端的服务

```bash
# systemctl enable shadowsocks-libev
# systemctl start shadowsocks-libev
# nginx -s reload
```

### 客户端配置

