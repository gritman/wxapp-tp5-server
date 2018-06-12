<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/6
 * Time: 20:20
 */

namespace app\api\validate;


class TokenGet extends BaseValidate {
    protected $rule = [
        'code' => 'require|isNotEmpty' // isNotEmpty防止传空值
    ];

    protected $message = [
        'code' => '获取token需要传入code'
    ];

}