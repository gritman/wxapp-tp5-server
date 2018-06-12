<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/5
 * Time: 18:05
 */

namespace app\lib\exception;


class ThemeException extends BaseException {
    public $code = 404;
    public $msg = '指定的主题不存在，请检查id';
    public $errorCode = 30000;
}