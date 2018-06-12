<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/1
 * Time: 14:19
 */

namespace app\api\model;


use app\lib\exception\BannerMissException;
use think\Db;
use think\Exception;
use think\Model;

class Banner extends BaseModel
{
    protected $table = 'banner';

    public function items()
    {
        // 一对多：Banner关联————BannerItem模型的模型名，
        //BannerItem模型的外键（通过此外键关联到Banner），Banner模型的主键（通过此键被BannerItem模型关联）
        // 语义：一个banner下有多个banner_item，是通过banner_item.banner_id和banner.id关联起来的
        // Banner hasMany BannerItem, BannerItem.banner_id, Banner.id
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    public static function getBannerById($id) {
        //$result = Db::query('select * from banner_item where banner_id = ?', [$id]);
        //return $result;
        //throw new \Exception('getBannerById wrong');
        //throw new BannerMissException();
        //$result = Db::table('banner_item')->where('banner_id', '=', $id)->select();
        $result = Banner::with(['items', 'items.img'])->find($id);
        return $result;
    }
}