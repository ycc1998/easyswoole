<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/17
 * Time: 21:39
 */
namespace App\HttpController\Model;
use EasySwoole\Component\Di;
use EasySwoole\Mysqli\Mysqli;

class BaseModel{

    /**
     * BaseModel constructor.
     * @throws \Throwable
     */
    public function __construct()
    {
        if (empty($this->tableName)){
            throw new \Exception('tableName error');
        }

        $db = Di::getInstance()->get('MYSQL');
        if ($db instanceof Mysqli){
            $this->db = $db;
        }else{
            throw new \Exception('db error');
        }
    }

    /**
     * 获取单条数据
     * @param $id
     */
    public function getById($id){
        $this->db->where('id',$id);
        $result = $this->db->getOne($this->tableName);
        return $result ?? [];

    }

    public function add($data)
    {
        if (empty($data) || !is_array($data)){
            return false;
        }

        return $this->db->insert($this->tableName,$data);
    }
}