<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;

class IndexController extends PmsApiController
{
    public function indexAction()
    {
        return '您好！这是一个[pms]示例应用';
    }
}
