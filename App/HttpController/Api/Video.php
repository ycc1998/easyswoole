<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/17
 * Time: 20:03
 */
namespace App\HttpController\Api;
use App\HttpController\Model\Video as VideoModel;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Validate\Validate;

class Video extends BaseController
{

    /**
     * 视频详情
     * @return bool|void
     * @throws \Throwable
     */
    public function index()
    {
        $id = intval($this->request()->getRequestParam('id'));
        if (empty($id)){
            return $this->writeJson(400,'','请求参数有误');
        }
        try {
            $video = (new VideoModel())->getById($id);
        }catch (\Exception $e){
            //错误信息记录日志
            return $this->writeJson(400,[],'查询信息出错！');
        }
        //状态0 表示不显示
        if (empty($video) || $video['status'] == 0){
            return $this->writeJson(400,[],'该视频不存在');
        }
        //投递task任务处理播放量
        TaskManager::async(function ()use ($id){
            Di::getInstance()->get('REDIS')->zIncrBy(\Yaconf::get('config.video_play_ley'),1,$id);
        });

        return $this->writeJson(200,$video,'OK');

    }

    /**
     * 排行榜
     * @return bool
     * @throws \Throwable
     */
    public function rank() {
        $result = Di::getInstance()->get("REDIS")->zRevRange(\Yaconf::get("config.video_play_ley"), 0, -1, "withscores");
        // 留给大家一个作业， 数据完善下
        return $this->writeJson(200, $result,'OK');
    }

    /**
     * 视频新增
     * @return bool
     * @throws \Throwable
     */
    public function add()
    {
        $parms = $this->request()->getRequestParam();
        $validate = new Validate();
        $validate->addColumn('name')->required('标题必填');
        $validate->addColumn('cat_id')->required('分类必填');
        $validate->addColumn('image')->required('封面图片必填');
        $validate->addColumn('url')->required('视频不能为空');
        $validate->addColumn('content')->required('内容不能为空');
        if(!$validate->validate($parms)){
           return $this->writeJson(400,'',$validate->getError()->__toString());
        }
        $data = [
            'name' => $parms['name'],
            'cat_id' => $parms['cat_id'],
            'image' => $parms['image'],
            'url' => $parms['url'],
            'content' => $parms['content'],
        ];

        try {
            $video = new VideoModel();
            $res = $video->add($data);
        }catch (\Exception $e){
            return $this->writeJson(400,'',$e->getMessage());
        }
        if (empty($res)){
            return $this->writeJson(400,'','添加失败');
        }else{
            return $this->writeJson(200,'','OK');
        }
    }
}