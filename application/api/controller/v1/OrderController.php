<?php

namespace app\api\controller\v1;

use app\api\service\OrderService;
use app\api\service\TokenService;
use app\api\validate\OrderPlace;


class OrderController extends BaseController
{
    //【从选商品到支付成功的流程】
    //1 用户选择商品后，向api提交包含它所选择商品的相关信息
    //2 api接收到信息后，检查订单相关商品的库存量，
    //因为客户端的库存量和服务端的库存量不一定是一样的
    //有技术可以让实时同步，比如长连接和websocket，但是维护难度大，程度高，但是仍有秒级误差
    //所以还是要服务器做A库存量检测
    //3 如果有库存，把订单数据存入数据库中，等同于下单成功，并且返回给客户端可以支付了
    //4 客户端收到可以支付消息后，调用支付接口，进行支付
    //支付扣款时，还需要B检测库存量，因为从下单到支付，还有时间间隔
    //5 调用微信的支付接口进行支付，此时用户还可能会取消支付，余额不足，支付失败
    //但是微信会返回支付结果，成功或失败
    //6 根据微信返回的支付结果，成功：C进行库存量检测，再扣除，失败：返回支付失败的结果
    //7 支付成功或失败，是谁返回给谁？

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    public function placeOrder() {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $orderService = new OrderService();
        $status = $orderService->place($uid, $products);
        return $status;
    }
 }