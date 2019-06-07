<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/20
 * Time: 21:06
 */
namespace App\HttpController\Lib\Crontab;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class Video extends AbstractCronTask{

    public static function getRule(): string
    {

        // 定时周期 （每10分钟）
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        // TODO: Implement getTaskName() method.
        // 定时任务名称
        return 'cacheData';
    }

    /**
     * 定时任务逻辑代码处理
     * @param \swoole_server $server
     * @param int $taskId
     * @param int $fromWorkerId
     * @param null $flags
     * @throws \Throwable
     */
    static function run(\swoole_server $server, int $taskId, int $fromWorkerId,$flags=null)
    {
        $category = array_keys(\Yaconf::get('category.cats'));
        array_unshift($category,0);

        $video = new \App\HttpController\Model\Video();

        foreach ($category as $v) {
            $condition = [];
            if (!empty($v)) {
                $condition['cat_id'] = $v;
            }
            try {
                $result = $video->getVideoCacheData($condition);
            }catch (\Exception $e){
                //可做通知处理
                $result = [];
                var_dump($e->getMessage(),'Lib/Crontab/Video');
            }

            if (empty($result)){
                var_dump("没用数据请重视分类id{$v}",'Lib/Crontab/Video');
                continue;//跳出本次循环
            }
            $cacheType = \Yaconf::get('config.cacheType');

            switch ($cacheType){
                case 'file':
                    $dir = EASYSWOOLE_ROOT.'/webroot/video/json/'.$v.'.json';
                    file_put_contents($dir,json_encode($result));
                    break;
                case 'redis':
                    $redis = Di::getInstance()->get('REDIS');
                    $redis->set('indexDataVideo_'.$v,$result);
                    break;
                default:
                    throw new \Exception('缓存类型不存在Lib/crontab/video.php');
                    break;
            }


            //通过文件形式缓存数据
            //$dir = EASYSWOOLE_ROOT.'/webroot/video/json/'.$v.'.json';
            //file_put_contents($dir,json_encode($result));

            //通过redis缓存首页数据
//            $redis = Di::getInstance()->get('REDIS');
//            $redis->set('indexDataVideo_'.$v,$result);
        }


    }
}