<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/7
 * Time: 13:47
 */

namespace app\lib\exception;


class WxException extends BaseException {
    public $code = 400;
    public $msg = '微信服务器接口调用失败';
    public $errorCode = 999;
}