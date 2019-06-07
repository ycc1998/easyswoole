<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/17
 * Time: 21:39
 */
namespace App\HttpController\Model;
class Video extends BaseModel{
    protected $tableName = 'video';

    /**
     * 查询首页展示数据
     * @param $condition
     * @param int $page
     * @param int $size
     * @return array
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public  function getVideoData($condition,$page = 1,$size = 10)
    {
        if (!empty($condition['cat_id'])){
            $this->db->where('cat_id',$condition['cat_id']);
        }
        $this->db->where('status',1);
        $this->db->orderBy('id','desc');
        $result = $this->db->get($this->tableName,[($page-1)*$size,$size],'id,name,image,content,uploader,create_time');

        foreach ($result as &$v){
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
        }

        if (!empty($condition['cat_id'])){
            $this->db->where('cat_id',$condition['cat_id']);
        }
        $this->db->where('status',1);
        $total = $this->db->count($this->tableName);
        return [
            'total_page' => ceil($total/$size),//向上取整
            'page_size' => $size,
            'count' => intval($total),
            'lists' => $result,
        ];
    }

    /**
     * 缓存数据
     * @param $condition
     * @param int $page
     * @param int $size
     * @return array
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\OrderByFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public  function getVideoCacheData($condition,$page = 1,$size = 1000)
    {

        if (!empty($condition['cat_id'])){
            $this->db->where('cat_id',$condition['cat_id']);
        }
        $this->db->where('status',1);
        $this->db->orderBy('id','desc');
        $result = $this->db->get($this->tableName,[($page-1)*$size,$size],'id,name,image,content,uploader,create_time');

        foreach ($result as &$v){
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
        }


        return $result;
    }
}