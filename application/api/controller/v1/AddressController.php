<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\User;
use app\api\service\TokenService;
use app\api\validate\AddressNew;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;

class AddressController extends BaseController {

    // 前置方法的调用关系
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

    /**
     * 通过json传的请求参数
     * @return \think\response\Json
     * @throws UserException
     * @throws \app\lib\exception\ParamException
     * @throws \think\exception\DbException
     */
    public function createOrUpdateAddress() {
        $validate = new AddressNew();
        $validate->goCheck();
        // 根据token获取uid
        // 根据uid来查找用户数据，判断用户是否存在，如果不存在则抛出异常
        // 获取用户从客户端提交的地址信息
        // 根据用户地址信息是否存在，从而判断是创建地址还是更新地址
        $uid = TokenService::getCurrentUid();
        $user = User::get($uid);
        if(empty($user)) {
            throw new UserException();
        }
        $paramArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        // 如果$userAddress为空，说明没关联到地址，那么就要创建地址。
        // 如果不为空，说明关联到了地址，那么就要更新地址
        if(empty($userAddress)) {
            $user->address()->save($paramArray);
        } else {
            $user->address->save($paramArray);
        }
        return json(new SuccessMessage(), 201);
    }
}
