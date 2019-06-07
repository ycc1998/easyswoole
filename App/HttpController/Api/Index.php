<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/11
 * Time: 16:37
 */
namespace App\HttpController\Api;


use App\HttpController\Lib\Es\EsVideo;
use EasySwoole\Component\Di;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;
use App\HttpController\Model\Video;
use Elasticsearch\ClientBuilder;

class Index extends BaseController {
    /**
     * 站内搜索
     * @return bool|void
     * name  查询关键词
     * key   在那个字段中查询
     */
    public function index()
    {
        $esvideo = new EsVideo();
        $parem = $this->request()->getRequestParam();
        $parem['page'] = empty($parem['page']) ? 0 : $parem['page'];
        $parem['size'] = empty($parem['size']) ? 10 : $parem['size'];
        $result = $esvideo->search($parem['name'],$parem['key'],$parem['page'],$parem['size']);

        $arr = [
            'total_page' => ceil($result['hits']['total']/$parem['size']),//向上取整
            'page_size' => $parem['size'],
            'count' => intval($result['hits']),
            'lists' => $result['hits']['hits'],
        ];

        return $this->writeJson(200,$arr,'ok');

    }

    /**
     * 首页展示方案一 直接mysql读取
     * 查询首页数据
     * @return bool
     * @throws \Throwable
     */
    public function lists1()
    {
        $condition = [];
        $param = $this->request()->getRequestParam();
        $page = !empty($param['page']) ? $param['page'] : 1;

        if(!empty($param['cat_id'])) {
            $condition['cat_id'] = intval($param['cat_id']);
        }
        try {
            $videoObj = new Video();
            $res =$videoObj->getVideoData($condition, $page);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return $this->writeJson(200,$res,'OK');
    }


    /**
     * 方案二 静态文件读取
     * @return bool
     */
    public function lists()
    {
        $param = $this->request()->getRequestParam();
        $cateId = empty($param['cat_id']) ? 0 : $param['cat_id'];
        $page = !empty($param['page']) ? $param['page'] : 1;
        $this -> size = !empty($param['size']) ? $param['size'] : 10;
        $form = ($page - 1) * $this -> size;

        $cacheType = \Yaconf::get('config.cacheType');

        try {
            switch ($cacheType){
                case 'file':
                    //通过文件形式获取缓存数据
                    $dir = EASYSWOOLE_ROOT.'/webroot/video/json/'.$cateId.'.json';
                    $videoData = is_file($dir) ? file_get_contents($dir) : []; //判断是不是文件
                    break;
                case 'redis':
                    $videoData = Di::getInstance()->get('REDIS')->get('indexDataVideo_' . $cateId);
                    break;
                default:
                    throw new \Exception('缓存类型不存在index/list.php');
                    break;
            }
        }catch (\Exception $e){
            return $this->writeJson(400,'','缓存类型存在错误');
        }
        $videoData = empty($videoData) ? [] : json_decode($videoData,true);

        $count = count($videoData);
        $videoData = array_splice($videoData,$form,$this->size);

        $data = $this->getPagingDatas($count,$videoData);
        return $this->writeJson(200,$data,'OK');

    }

    public function redis()
    {
//        $redis = new \Redis();
////        $redis->connect('127.0.0.1','6379');
////        $redis->set('name','jiushiwo');
////        $this->response()->write($redis->get('name'));

        $redis = (Di::getInstance())->get('REDIS');





       return $this->response()->write($redis->get('name'));
    }
}
