<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/11
 * Time: 15:38
 */

namespace app\lib\exception;


class UserException extends BaseException {
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}