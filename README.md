## 一个 php 版的 httpbin

### demo
> http://httpbin.mykeep.fun

### 安装
1. 检查环境依赖
```php
❯ composer check-platform-reqs
ext-json       7.4.2    success  
ext-pcre       7.4.2    success  
ext-swoole     4.5.2    success  
ext-tokenizer  7.4.2    success  
php            7.4.2    success   

```
> 当不满足时，推荐 [phpbrew](http://blog.mykeep.fun/2021/08/29/PHP-%E5%A4%9A%E7%89%88%E6%9C%AC%E7%AE%A1%E7%90%86-phpbrew/)

2. 运行 - debug 模式
```php
php index.php server:watch -vvv
```

### swagger
```phpregexp
php swagger.php
```

### better

1. php-cs-fixer
```phpregexp
php `which composer` run-script cs-fix ./
```

### 部署
```bash
# 1. nginx 配置文件
upstream httpbin {
	server 127.0.0.1:9501;
}

server {
	listen       80;
	server_name httpbin.mykeep.fun;

        location /static/ {
	   index index.html;
	   root /work/apps/httpbin.kit/src;
	}
	location / {
		proxy_set_header Host $http_host;
		proxy_set_header X-Real-IP $remote_addr;
		proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
		proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";
        # 执行代理访问真实服务器
		proxy_pass http://httpbin;
	}
	index  index.html index.htm;
	error_page   500 502 503 504  /50x.html;
	location = /50x.html {
		root   /usr/share/nginx/html;
	}

	access_log  /var/log/nginx/httpbin.access.log  main;

}

# 2. 配置 supervisor
# 新建一个应用并设置一个名称，这里设置为 hyperf
[program:httpbin]
# 设置命令在指定的目录内执行
directory=/work/apps/httpbin.kit
# 这里为您要管理的项目的启动命令
command=/home/work/.phpbrew/php/php-7.4.2/bin/php index.php start
# 以哪个用户来运行该进程
user=work
# supervisor 启动时自动该应用
autostart=true
# 进程退出后自动重启进程
autorestart=true
# 进程持续运行多久才认为是启动成功
startsecs=1
# 重试次数
startretries=3
# stderr 日志输出位置
stderr_logfile=/work/logs/httpbin/stderr.log
# stdout 日志输出位置
stdout_logfile=/work/logs/httpbin/stdout.log
stdout_logfile_maxbytes=20MB
stdout_logfile_backups=20

```

### 灵感
[httpbin](http://httpbin.org)

### todo
- [x] cors 处理
- [x] GET
- [x] POST 
- [x] PUT 
- [x] DELETE 
- [x] PATCH 
- [x] swagger
