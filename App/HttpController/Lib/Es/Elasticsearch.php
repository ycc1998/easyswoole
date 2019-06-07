<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/6/7
 * Time: 14:03
 */
namespace App\HttpController\Lib\Es;
use EasySwoole\Component\Singleton;
use Elasticsearch\ClientBuilder;

class Elasticsearch {
    use Singleton;

    public $client = NULL;
    public function __construct()
    {
        $host = \Yaconf::get('es.host');

        try{
            $this->client = ClientBuilder::create()
                ->setHosts([$host])
                ->build();
        }catch (\Exception $e){
            throw new \Exception('es连接错误');
        }
        if(empty($this->client)){
            throw new \Exception('es连接失败');
        }

    }

    public function __call($name, $arguments)
    {
        return $this->client->$name(...$arguments);

    }
}