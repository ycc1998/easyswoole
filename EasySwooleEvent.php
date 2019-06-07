<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\HttpController\Lib\Crontab\Video;
use App\HttpController\Lib\Es\Elasticsearch;
use App\HttpController\Lib\Process\ConsumerTest;
use App\HttpController\Lib\Redis\Redis;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Mysqli\Mysqli;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.

        Di::getInstance()->set('REDIS',Redis::getInstance());
        Di::getInstance()->set('MYSQL',new Mysqli(new \EasySwoole\Mysqli\Config(\Yaconf::get('mysql'))));
        Di::getInstance()->set('ES',Elasticsearch::getInstance());

        //队列消费
        $allNum = 3;
        for ($i = 0 ;$i < $allNum;$i++){
            ServerManager::getInstance()
                ->getSwooleServer()
                ->addProcess((new ConsumerTest("consumer_{$i}"))->getProcess());
        }

        //定时缓存api数据
        Crontab::getInstance()->addTask(Video::class);

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}