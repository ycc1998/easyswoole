<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/6/7
 * Time: 14:44
 */
namespace App\HttpController\Lib\Es;
class EsVideo extends EsBase {
    /**
     * @var string 索引
     */
    public $index = 'video';
    /**
     * @var string 
     */
    public $type = 'video';
}