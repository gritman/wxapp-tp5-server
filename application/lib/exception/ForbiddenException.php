<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/12
 * Time: 20:40
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException {
    public $code = 403; // HTTP状态码 200 404 500等等
    public $msg = '权限不够'; // 具体错误信息
    public $errorCode = 10001; // 自定义的错误码
}