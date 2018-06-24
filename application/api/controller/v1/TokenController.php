<?php

namespace app\api\controller\v1;

use app\api\service\UserTokenService;
use app\api\validate\TokenGet;
use think\Controller;
use think\Request;

class TokenController extends Controller
{
    /**
     * POST 因为code有安全性要求，用get放在url路径里不安全，所以要用post稍微提升安全性
     * 如果要解决安全问题，还是要用https才最安全
     * login是动作，但是getToken是获取资源，和rest理念一致
     *
     * @param string $code
     * @throws \app\lib\exception\ParamException
     */
    public function getToken($code = '') {
        (new TokenGet())->goCheck();
        $token = (new UserTokenService($code))->get($code);
        // 不要直接返回字符串，而要返回成关联数组，框架会自动转成json
        return ['token' => $token];
    }
}
