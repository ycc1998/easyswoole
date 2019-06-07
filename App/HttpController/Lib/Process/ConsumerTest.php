<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/12
 * Time: 20:11
 */
namespace App\HttpController\Lib\Process;

use App\HttpController\Lib\Redis\Redis;
use EasySwoole\Component\Process\AbstractProcess;

class ConsumerTest extends AbstractProcess{
    private $isRun = false;
    public function run($arg)
    {
        // TODO: Implement run() method.
        /*
         * 举例，消费redis中的队列数据
         * 定时500ms检测有没有任务，有的话就while死循环执行
         */
        $this->addTick(500,function (){
            if(!$this->isRun){
                $this->isRun = true;
                $redis = Redis::getInstance();
                while (true){
                    try{
                        $task = $redis->lPop('name');
                        if($task){
                            //var_dump($task);
                        }else{
                            break;
                        }
                    }catch (\Throwable $throwable){
                        break;
                    }
                }
                $this->isRun = false;
            }
            //var_dump($this->getProcessName().' task run check');
        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
    }
}