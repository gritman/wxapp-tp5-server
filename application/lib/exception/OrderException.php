<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/14
 * Time: 20:45
 */

namespace app\lib\exception;


class OrderException extends BaseException {
    public $code = 404;
    public $msg = '订单不存在，请检查id';
    public $error = 80000;

}