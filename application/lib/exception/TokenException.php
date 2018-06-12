<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/9
 * Time: 12:17
 */

namespace app\lib\exception;


class TokenException extends BaseException {
    public $code = 401;
    public $msg = 'token已过期或无效';
    public $errorCode = 10001;
}