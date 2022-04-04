### resolve.conf 重启失效

修改 `/etc/resolvconf.conf`

加入以下行即可：

```
name_servers="2001:67c:2b0::4 2001:67c:2b0::6"
```


> ./node_modules/gatling/gatling.js:59:    port = argv.port || process.env.PORT || process.env.VCAP_APP_PORT || 8080,
> ./node_modules/gatling/test/test.js:20:            request('http://localhost:8080/ok', function(err, data) {
> ./node_modules/gatling/test/test.js:35:            request('http://localhost:8080/slow', function(err, data) {
./node_modules/gatling/test/test.js:46:            request('http://localhost:8080/error', function(err, data) {
./node_modules/gatling/test/test.js:54:            request('http://localhost:8080/error', function(err, data) {
./node_modules/gatling/test/test.js:55:                request('http://localhost:8080/ok', function(err, data) {
./node_modules/gatling/test/test.js:73:            request('http://localhost:8080/ok', function(err, data) {
