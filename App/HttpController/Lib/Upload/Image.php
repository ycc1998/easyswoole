<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/15
 * Time: 21:00
 */
namespace App\HttpController\Lib\Upload;
class Image extends Base{

    /**
     * 上传视频文件的键名
     * @var string
     */
    public $fileType = 'image';

    /**
     * 文件大小
     * @var int
     */
   // public $maxSize = 100;

    /**
     * 文件类型
     * @var array
     */
    public $fileExtTypes = [
        'png',
        'jpg',
        'jpeg',
    ];
}