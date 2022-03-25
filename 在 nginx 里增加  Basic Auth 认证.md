## 生成密码

使用下列命令生成密码：

```
$ printf "YOUR_USERNAME:$(openssl passwd -crypt YOUR_PASSWORD)\n" >> /etc/nginx/conf.d/passwd

## 配置 Nginx

在 nginx 的配置文件里增加以下内容

```
...

location / {
        auth_basic "Hello World";
        auth_basic_user_file conf.d/passwd;
        
...
```
