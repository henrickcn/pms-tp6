<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;

class IndexController extends PmsApiController
{
    public function indexAction()
    {
        return static::_error(0,'成功',[
            'userInfo' => [
                'oa_name'   => $this->userInfo['oa_name'],
                'nick_name' => $this->userInfo['nick_name'],
                'avatar'    => $this->userInfo['avatar'],
            ]
        ]);
    }
}
