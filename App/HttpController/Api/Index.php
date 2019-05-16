<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/11
 * Time: 16:37
 */
namespace App\HttpController\Api;


use App\HttpController\Lib\Redis\Redis;
use EasySwoole\Mysqli\Config;
use EasySwoole\Mysqli\Mysqli;

class Index extends BaseController {
    public function index()
    {

        $conf = new Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL'));
        //$db = new Mysqli($conf);
        //$data = $db->get('user');//获取一个表的数据
        $this->response()->write('hello world');
        //$this->writeJson(200,$data,'success');
        // TODO: Implement index() method.
    }

    public function redis()
    {
//        $redis = new \Redis();
////        $redis->connect('127.0.0.1','6379');
////        $redis->set('name','jiushiwo');
////        $this->response()->write($redis->get('name'));

        $redis = Redis::getInstance();





        $this->response()->write($redis->get('name'));
    }
}
