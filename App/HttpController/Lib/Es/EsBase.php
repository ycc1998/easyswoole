<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/6/7
 * Time: 14:45
 */
namespace App\HttpController\Lib\Es;
use EasySwoole\Component\Di;

class EsBase{

    public $client = null;
    public function __construct()
    {
        $this->client = Di::getInstance()->get('ES');
    }

    /**
     * @param $name 需要查询的字符
     * @param $key 在那个键名中查询
     * @param string $type 查询类型
     * @return mixed
     */
    public function search($name,$key,$page,$size=10,$type='match'){
        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'body' => [
                'query' => [
                    $type => [
                        $key => $name
                    ]
                ],
                'from'=>($page-1)*$size,
                'size'=>$size
            ]
        ];

        return $this->client->search($params);
    }
}