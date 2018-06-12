<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/6
 * Time: 12:11
 */

namespace app\lib\exception;

class CategoryException extends BaseException {
    public $code = 404; // HTTP状态码 200 404 500等等
    public $msg = '类目不存在'; // 具体错误信息
    public $errorCode = 50000; // 自定义的错误码
}