<?php

namespace app\api\controller\v1;

use app\api\model\Theme;
use app\api\validate\IdCollection;
use app\api\validate\IdMustBePositiveInt;
use app\lib\exception\ThemeException;
use think\Controller;

class ThemeController extends Controller
{
    /**
     * url /theme?ids=id1,id2,di3,...
     * return 一组theme模型
     */
    public function getSimpleList($ids = '') {
        (new IdCollection())->goCheck();
        $ids = explode(',', $ids);
        // 返回的是Theme模型数组，数据集
        $result = Theme::with(['topicImg', 'headImg'])->select($ids);
        if(empty($result)) {
            throw new ThemeException();
        }
        return $result;
    }

    /**
     * @url api/:v/theme/:id
     * @param $id
     * @throws \app\lib\exception\ParamException
     *
     */
    public function getComplexOne($id) {
        (new IdMustBePositiveInt())->goCheck();
        $result = Theme::getThemeWithProducts($id);
        if(empty($result)) {
            throw new ThemeException();
        }
        return $result;
    }
}


