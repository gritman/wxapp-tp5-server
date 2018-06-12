<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/6
 * Time: 21:57
 */

return [
    'app_id' => 'wx5d3769ea6cedb79c',
    'app_secret' => 'e051ef90f7ea5f9c0f50b0abcfe4f9a5',
    // 用code换取openid的接口
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code'
];