## 编译 nginx

下载 nginx 源码，以及 pcre、openssl、zlib。

```
wget https://nginx.org/download/nginx-1.20.2.tar.gz
wget https://jaist.dl.sourceforge.net/project/pcre/pcre/8.45/pcre-8.45.tar.gz
wget https://www.openssl.org/source/openssl-1.1.1f.tar.gz
wget http://zlib.net/fossils/zlib-1.2.11.tar.gz
```

解压缩以上文件。

下载 ngx_http_google_filter_module 及 ngx_http_substitutions_filter_module 两个模块。   

```
git clone https://github.com/cuber/ngx_http_google_filter_module
git clone https://github.com/yaoweibin/ngx_http_substitutions_filter_module
```

在其他 ubuntu 里用 `nginx -V` 命令看一下安装配置参数，拷贝过来添加在 `./configure` 之后，再在其后添加上：

```
--with-pcre=../pcre-8.45 \
--with-openssl=../openssl-1.1.1f \
--with-zlib=../zlib-1.2.11 \
--add-module=../ngx_http_google_filter_module \
--add-module=../ngx_http_substitutions_filter_module
```

运行以上命令。如果提示缺少某个模块，在配置参数里删除相应模块即可。

`make` 并且 `make install` 安装 nginx 。

## 增加 Basic Auth 认证

如果不想自己的谷歌镜像被别人乱用，可以增加 Basic Auth 来限制其他人使用。

## 生成密码

使用下列命令生成密码：

```
$ printf "YOUR_USERNAME:$(openssl passwd -crypt YOUR_PASSWORD)\n" >> /etc/nginx/conf.d/passwd
```

## 编辑 nginx 配置文件

之后，编辑 nginx.conf ，主要部分如下：

```
# upstream配置google的ip，ip可以通过 nslookup www.google.com.hk 命令获取，
# 多运行几次nslookup会获取到多个IP，有助于避免触发google的防机器人检测。
# 关闭vps的wgcf，只用google的ipv6地址。这样可以防止弹出“同意cookie”的对话框（可能是通过 cloudflare CDN 的缘故）。

upstream www.google.com.hk {
    server [2a00:1450:4010:c0f::5e]:443 weight=1;
    server [2a00:1450:4010:c0d::5e]:443 weight=1;
    server [2a00:1450:400f:800::2003]:443 weight=1;
    server [2a00:1450:4001:80f::2003]:443 weight=1;
}

server {
    listen 80;
    listen [::]:80;
    server_name www.example.com;
    charset utf-8;

    # Enforce HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2 default_server;
    listen [::]:443 ssl http2 default_server;
    ssl_certificate       /path_to_cert/www.example.com/signed_chain.crt;
    ssl_certificate_key   /path_to_cert/www.example.com/domain.key;
    ssl_protocols         TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers           HIGH:!aNULL:!MD5;
    server_name         www.example.com;
    index index.html index.htm index.nginx-debian.html;
    # root  /var/www/html;
    error_page 400 = /400.html;

    location / {
        auth_basic "Hello World";
        auth_basic_user_file conf.d/passwd;

        # 因为套了 cloudflare 的香港 CDN，所以这里地址是 www.google.com.hk 而不是 www.google.com
        proxy_pass https://www.google.com.hk/;
 
        proxy_redirect https://www.google.com.hk/ /;
        # 把以下 www.example.com 替换为你自己的域名
        proxy_cookie_domain google.com.hk www.example.com;
 
        proxy_set_header User-Agent $http_user_agent;
        proxy_set_header Cookie "PREF=ID=047808f19f6de346:U=0f62f33dd8549d11:FF=2:LD=zh-CN:NW=1:TM=1325338577:LM=1332142444:GM=1:SG=2:S=rE0SyJh2W1IQ-Maw";
  
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
 
        subs_filter  http://www.google.com.hk https://www.example.com;   
        subs_filter  https://www.google.com.hk https://www.example.com;        
    }
}
```
