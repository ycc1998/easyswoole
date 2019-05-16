<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/11
 * Time: 17:54
 */
namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class BaseController extends Controller{


    public function index()
    {
        // TODO: Implement index() method.
    }

    /**
     * 可用权限控制
     * @param string|null $action
     * @return bool|null
     */
    protected function onRequest(?string $action): ?bool
    {
        //$this->writeJson(403,'','你灭有权限');
        return true;
    }

    /**
     * 处理异常
     * @param \Throwable $throwable
     * @throws \Throwable
     */
    protected function onException(\Throwable $throwable): void
    {
        throw $throwable;
        //$this->writeJson(500,'','内部服务器错误');
    }




}
