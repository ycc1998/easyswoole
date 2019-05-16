<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/11
 * Time: 16:37
 */
namespace App\HttpController\Api;

use App\HttpController\Lib\Upload\Video;
use EasySwoole\Http\Message\UploadFile;


class Upload extends BaseController {

    /**
     * 上传文件
     */
    public function file()
    {
        $request = $this->request();
        try{
            $video = new Video($request);
            $file = $video->upload();
        }catch (\Exception $e){
            return $this->writeJson(400,'',$e->getMessage());
        }
        if (empty($file)){
            return $this->writeJson(400,'','上传失败');
        }else{
            $data = [
                'url' => $file
            ];
            return $this->writeJson(200,$data,'ok');
        }
        /*
        $request = $this->request();
        //获取上传文件  对象
        //$viseos = $request->getUploadedFile("file");
        //获取上传文件 数组
        $result = $request->getSwooleRequest()->files;
        var_dump($result);
        //$result = $viseos->moveTo('/1.jpg');
        $this->response()->write("11");*/


    }


}
