# 简介    
基于 tp3.2    
使用了 mysql    
id 为长度 255 以内的字符串   
使用了缓存，缓存用 redis    
访问时从缓存中读取数据    
使用 cron 同步数据到数据库    
一共有三个方法    
view    
read    
fromCacheToDatabase    
view 返回值为 +1 后的最新计数    
read 返回值为当前计数（不进行 +1 ）    
fromCacheToDatabase 是同步数据库用的    
#### crond
```sh
crond :    
  service : on    
  crontab :    
    - "0 3 * * * curl http://localhost/thinkphp/index.php/IdCount/index/fromCacheToDatabase"    
```
#### 功能
程序每次被调用时，将对应ID的计数值 +1    
调用    
调用形式是 URL GET 请求， http://host.com/thinkphp/index.php/IdCount/index/view?id=文章ID    
程序接收的参数：文章 ID    
返回    
JSON 格式如：
{
"count":123
}

#### js 调用
1. 在页面里引入 exmaple 文件夹的 pageCount.js    
pageCount.js 里暴露了一个 wenkuCounter 的全局对象    
2. 自增调用    
var current = wenkuCount.read(id, function(count){});    
3. 读数调用    
var lastest = wenkuCount.view(id, function(count){});    

#### 表结构及样例数据
```sql
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `think_count`;
CREATE TABLE `think_count` (
  `id` varchar(255) NOT NULL,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `think_count`(`id`,`count`) values
('1','6'),
('12','11'),
('123','24'),
('999','3'),
('1234','9'),
('12345','12');
SET FOREIGN_KEY_CHECKS = 1;
````
