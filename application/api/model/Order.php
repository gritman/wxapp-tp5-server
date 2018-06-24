<?php

namespace app\api\model;

use think\Model;

class Order extends BaseModel {
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
}
