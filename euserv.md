### resolve.conf 重启失效

修改 `/etc/resolvconf.conf`

加入以下行即可：

```
name_servers="2001:67c:2b0::4 2001:67c:2b0::6"
```

