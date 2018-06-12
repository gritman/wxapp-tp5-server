<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/2
 * Time: 16:51
 */

namespace app\lib\exception;


class ParamException extends BaseException
{
    public $code = 499;
    public $msg = '参数错误';
    public $errorCode = 10000;
}