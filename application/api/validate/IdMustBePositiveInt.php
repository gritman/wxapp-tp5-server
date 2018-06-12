<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/5/31
 * Time: 14:18
 */

namespace app\api\validate;


class IdMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];

}