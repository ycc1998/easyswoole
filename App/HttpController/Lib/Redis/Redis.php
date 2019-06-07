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

    /**
     *当方法不存在，执行该魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {

        return $this->redis->$name(...$arguments);
    }

    public function get($key='')
    {
        if(empty($key)){
            return false;
        }
        var_dump($key);
        return $this->redis->get($key);
    }

    /**
     *插入数据
     *$time 单位秒
     */
    public function set($key = '',$value = '',$time = 0)
    {
        if (empty($key)) {
            return '';
        } else {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            if (!$time) {
                return $this->redis->set($key,$value);
            } else {
                return $this->redis->setex($key, $time, $value);
            }
        }
    }

    /**
     * 有序集合中对指定成员的分数加上增量 increment
     * @param $key
     * @param $num
     * @param $member
     * @return bool|float
     */
    public function zIncrBy($key,$num,$member)
    {
        if (empty($key) || empty($member)){
            return false;
        }
        return $this->redis->zIncrBy($key,$num,$member);
    }

    /**
     * 返回有序集中指定区间内的成员，通过索引，分数从高到底
     * @param $key
     * @param $start
     * @param $stop
     * @param $type
     * @return bool
     */
    public function zRevRange($key,$start,$stop,$type)
    {
        if (empty($key) || !isset($start) || empty($stop)){
            return false;
        }

        return $this->redis->zRevRange($key,$start,$stop,$type);

    }


    public function lPop($key='')
    {
        if(empty($key)){
            return false;
        }
        return $this->redis->lPop($key);
    }



}