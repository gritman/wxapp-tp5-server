<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/14
 * Time: 11:53
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;

class OrderService {
    // 订单的商品列表，客户端传递过来的products参数
    protected $oProducts;
    // 真实的商品信息（包括库存量）
    protected $products;
    // 用户id
    protected $uid;

    public function place($uid, $oProducts) {
        // oProducts和products做对比，才能检验库存量
        // products从数据库查出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;

        $orderStatus = $this->getOrderStatus();
        if(!$orderStatus['pass']) {
            // 库存量检测失败，不创建订单，直接返回
            $orderStatus['order_id'] = -1;
            return $orderStatus;
        } else {
            // 库存量检测通过，开始创建订单
            // 先创建订单快照
            $orderSnap = $this->snapOrder($orderStatus);
            $order = $this->createOrder($orderSnap);
            $order['pass'] = true;
            return $order;
        }
    }

    // 写入订单信息，返回订单id和订单编号
    private function createOrder($snapOrder) {
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snapOrder['orderPrice'];
            $order->total_count = $snapOrder['totalCount'];
            $order->snap_img = $snapOrder['snapImg'];
            $order->snap_name = $snapOrder['snapName'];
            $order->snap_address = $snapOrder['snapAddress'];
            $order->snap_items = json_encode($snapOrder['pStatus']);
            $order->save();
            // 插入order_product表，是关联表
            $orderId = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$oProduct) {
                $oProduct['order_id'] = $orderId;
            }
            $orderProduct = new OrderProduct();
            $dataToSave = $this->oProducts;
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $create_time
            ];
        } catch(Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    public static function makeOrderNo() {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    // 生成订单快照
    private function snapOrder($orderStatus) {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0, // 订单里的商品个数
            'pStatus' => [], // 商品状态
            'snapAddress' => '', // 收件地址
            'snapName' => '', // 订单概要商品名
            'snapImg' => '', // 订单概要图片
        ];
        $snap['orderPrice'] = $orderStatus['orderPrice'];
        $snap['totalCount'] = $orderStatus['totalCount'];
        $snap['pStatus'] = $orderStatus['pStatusArray'];
        // 把数据库记录，序列化后存储为一个字段，不太好，最好用mongodb来存储，这样方面以后检索
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if(count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    private function getUserAddress() {
        $userAddress = UserAddress::where('user_id', '=', $this->uid)->find();
        if(!$userAddress) {
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress->toArray();
    }

    // 根据订单的商品信息查找真实商品信息
    public function getProductsByOrder($oProducts) {
        $oPids = [];
        foreach($oProducts as $item) {
            array_push($oPids, $item['product_id']);
        }
        $products = Product::all($oPids)->visible(['id', 'price', 'stock', 'name', 'main_img_url'])->toArray();
        return $products;
    }

    // 对比oProducts和products
    private function getOrderStatus() {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];
        foreach($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'], $oProduct['count'], $this->products);
            if(!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    // 获取订单中某个商品的详细状态
    private function getProductStatus($oPid, $oCount, $products) {
        $pIndex = -1;
        $pStatus = [
            'id' => '',
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totolPrice' => 0 // 某个商品的总价格
        ];
        for($i = 0; $i != count($products); ++$i) {
            if($oPid == $products[$i]['id']) {
                $pIndex = $i;
            }
        }
        // 客户端传递的product_id不存在
        if($pIndex == -1) {
            throw new OrderException([
                'msg' => 'id为'.$oPid.'的商品不存在，创建订单失败'
            ]);
        }
        $product = $products[$pIndex];
        $pStatus['id'] = $product['id'];
        $pStatus['count'] = $oCount;
        $pStatus['name'] = $product['name'];
        $pStatus['totalPrice'] = $product['price'] * $oCount;
        $pStatus['haveStock'] = ($product['stock'] - $oCount >= 0) ? true : false;
        return $pStatus;
    }
}







