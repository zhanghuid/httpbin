## 一个 php 版的 httpbin

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
php index.php server:watch
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

### demo
> http://httpbin.mykeep.fun

### 灵感
[httpbin](http://httpbin.org)

### todo
- [x] cors 处理
- [x] GET
- [x] POST 
- [x] PUT 
- [x] DELETE 
- [x] PATCH 
- [ ] swagger
