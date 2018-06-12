<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/1
 * Time: 20:48
 */

namespace app\lib\exception;


class BannerMissException extends BaseException {
    public $code = 404;
    public $msg = '请求Banner不存在';
    public $errorCode = 40000;
}