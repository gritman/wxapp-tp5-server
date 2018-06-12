<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/5
 * Time: 23:14
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $message = [
        'count' => 'count参数必须是1到15之间的正整数，或者不传该参数'
    ];

    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];
}