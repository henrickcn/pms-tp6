<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;
use app\common\service\UserService;

class UserController extends PmsApiController
{
    /**
     * @var 用户服务模型
     */
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
        return parent::__construct();
    }

    /**
     * 登录接口
     * @return \think\Response
     */
    public function loginAction()
    {
        if($this->request->isPost()){
            $res = $this->_userService->login($this->request->post());
            return static::_error($res['errcode'], $res['errmsg'], $res['data']);
        }
        return static::_error(1, '无法访问此方法');
    }


}
