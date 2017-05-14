#说明    
基于tp3.2    
使用了mysql    
id为长度255以内的字符串   
使用了缓存，缓存用redis    
访问时从缓存中读取数据    
使用cron同步数据到数据库    
一共有三个方法    
view    
read    
fromCacheToDatabase    
view返回值为+1后的最新计数    
read返回值为当前计数（不进行+1）    
fromCacheToDatabase    是同步数据库用的    
#crond
crond :    
  service : on    
  crontab :    
    - "0 3 * * * curl http://localhost/thinkphp/index.php/IdCount/index/fromCacheToDatabase"    
#功能
程序每次被调用时，将对应ID的计数值+1    
调用    
调用形式是URL GET请求， http://host.com/thinkphp/index.php/IdCount/index/view?id=文章ID    
程序接收的参数：文章ID    
返回    
JSON 格式如：
{
"count":123
}

#js调用
在页面里引入exmaple文件夹的pageCount.js    
pageCount.js里暴露了一个wenkuCounter的全局对象    
自增调用    
var current = wenkuCount.read(id, function(count){});    
读数调用    
var lastest = wenkuCount.view(id, function(count){});    

#表结构及样例数据

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