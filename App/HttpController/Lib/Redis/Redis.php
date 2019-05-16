<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/11
 * Time: 21:32
 */
namespace App\HttpController\Lib\Redis;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;
class Redis{
    use Singleton;
    public $redis;

    /**
     * Redis constructor.
     * @throws \Exception
     */
    private function __construct()
    {
        if(!extension_loaded('redis')){
            throw new \Exception('redis.so不存在');
        }
        try{
            //连接配置
            //$redisConfig = Config::getInstance()->getConf('REDIS');
            //使用yaconf扩展设置配置参数
            $redisConfig = \Yaconf::get('redis');

            $this->redis = new \Redis();
            $result = $this->redis->connect($redisConfig['host'],$redisConfig['port'],$redisConfig['timeout']);
        }catch (\Exception $e){
            throw new \Exception('redis连接异常');
        }

        if($result === false){
            throw new \Exception('redis连接失败');
        }

    }

    public function get($key='')
    {
        if(empty($key)){
            return false;
        }
        return $this->redis->get($key);
    }

    public function lPop($key='')
    {
        if(empty($key)){
            return false;
        }
        return $this->redis->lPop($key);
    }
}