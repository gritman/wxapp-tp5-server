<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/5/29
 * Time: 23:41
 */

namespace app\api\controller\v2;

use app\api\model\Banner;
use app\api\validate\IdMustBePositiveInt;
use app\lib\exception\BannerMissException;
use think\Exception;
use think\Paginator;

class BannerController
{
    /**
     * 获取指定id的banner信息
     * url /banner/:id
     * http GET
     * param $id banner的id号，哪一个banner，因为前端可能有多个banner
     */
    public function getBanner($id) {
        return 'this is v2 getBanner';
    }
}

