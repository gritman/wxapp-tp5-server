<?php

namespace app\api\controller\v1;

use app\api\service\TokenService;
use think\Controller;
use think\Request;

class BaseController extends Controller
{
    protected function checkPrimaryScope() {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope() {
        TokenService::needExclusiveScope();
    }
}
