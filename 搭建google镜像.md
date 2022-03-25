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




之后，编辑 nginx.conf ，主要部分如下：

```
    location / {

        auth_basic "Hello World";
        auth_basic_user_file conf.d/passwd;

        proxy_pass https://www.google.com.hk/;
 
        proxy_redirect https://www.google.com.hk/ /;
        proxy_cookie_domain google.com.hk dmnik.nibtap.com;
 
        proxy_set_header User-Agent $http_user_agent;
        proxy_set_header Cookie "PREF=ID=047808f19f6de346:U=0f62f33dd8549d11:FF=2:LD=zh-CN:NW=1:TM=1325338577:LM=1332142444:GM=1:SG=2:S=rE0SyJh2W1IQ-Maw";
  
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
 
        subs_filter  http://www.google.com.hk https://dmnik.nibtap.com;
        subs_filter  https://www.google.com.hk https://dmnik.nibtap.com;        
    }
```
