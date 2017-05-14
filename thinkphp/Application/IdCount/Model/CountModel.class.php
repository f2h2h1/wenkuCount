<?php
namespace IdCount\Model;

use Think\Model;

class CountModel extends Model {

    private $countCache;
    private $key_list;

    public function __construct()
    {
        parent::__construct();
        $this->countCache = $this->init_cache();
        $this->key_list = "key_list";
    }
    //初始化缓存_redis
    private function init_cache()
    {
        /*从平台获取数据库名*/
        $dbname = ""; //数据库名称

        /*从环境变量里取host,port,user,pwd*/
        $host = '';
        $port = '';
        $user = ''; //用户AK
        $pwd = '';  //用户SK

        $redis = new \Redis();
        $ret = $redis->connect($host, $port);
        $ret = $redis->auth($user . "-" . $pwd . "-" . $dbname);
        return $redis;
    }
    //获取缓存里的值_redis
    public function get_cache($cache_key)
    {
        $redis = $this->countCache;
        $cache = $redis->get($cache_key);
        return $cache;
    }
    
    //往缓存里添加值_redis
    public function add_cache($cache_key, $cache_value)
    {
        $redis = $this->countCache;
        $ret1 = $redis->set($cache_key, $cache_value);
        $ret2 = $redis->lpush("key_list", $cache_key);
        if($ret1 > 0 && $ret2 > 0)
            return true;
        else
            return false;
    }
    
    //更新缓存里的值_redis
    public function updata_cache($cache_key, $cache_value)
    {
        $redis = $this->countCache;
        $cache_key = (string)$cache_key;
        $ret1 = $redis->set($cache_key, $cache_value);
        if($ret1 > 0)
            return true;
        else
            return false;
    }
    
    //获取缓存里所有值_redis
    public function get_all_cache()
    {
        $redis = $this->countCache;
        $key_list_size = $redis->lsize($this->key_list);
        $key_list = $redis->lrange($this->key_list, 0 ,$key_list_size);
        if(!empty($key_list))
            return $key_list;
        else
            return false;
    }
    //删除缓存里所有值_redis
    public function delete_all_cache()
    {
        $redis = $this->countCache;
        $key_list = $this->get_all_cache();
        foreach($key_list as $id) {
            $this->delete_cache($id);
        }
        $ret = $this->delete_cache($this->key_list);
    }
    
    //删除缓存里的值_redis
    public function delete_cache($cache_key)
    {
        $redis = $this->countCache;
        $ret = $redis->del($cache_key);
        if($ret > 0)
            return true;
        else
            return false;
    }
}
?>