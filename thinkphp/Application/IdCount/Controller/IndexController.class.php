<?php

namespace IdCount\Controller;

use Think\Controller;
use IdCount\Model\CountModel;

class IndexController extends Controller {

    private $countModel;
    private $countCache;

    public function __construct()
    {
        parent::__construct();
        $this->countModel = D('count');
        $this->countCache = new countModel();
    }
    public function fromDatabaseToCache()
    {
        $this->countCache->delete_all_cache();
        $result = $this->countModel->select();
        foreach($result as $value) {
            $this->countCache->add_cache($value['id'], $value['count']);
        }
    }
    public function fromCacheToDatabase()
    {
        $key_list = $this->countCache->get_all_cache();
        foreach($key_list as $id) {
            $id = (string)$id;
            $count = $this->countCache->get_cache($id);
            $result = $this->countModel->where('id="%s"', $id)->select();
            if ($result == false) {
                $this->countModel->add(array('id'=>$id,'count'=>$count));
            } else {
                $this->countModel->where('id="%s"',$id)->setField('count', $count);
            }
        }
    }
    public function view()
    {
        $id = I('get.id', 0);
        if (sizeof($id) < 256) {
            $count = $this->countCache->get_cache($id);
            if (empty($count)) {
                $count = 1;
                $this->countCache->add_cache($id, $count);
            } else {
                $count++;
            }
            $this->countCache->updata_cache($id, $count);
        }
        $this->show(json_encode(array("count" => $count)), 'utf-8');
    }
    public function read()
    {
        $id = I('get.id', 0);
        $count = $this->countCache->get_cache($id);
        $this->show(json_encode(array("count" => $count)), 'utf-8');
    }
    public function delete_all_cache()
    {
        $this->countCache->delete_all_cache();
    }
}

?>