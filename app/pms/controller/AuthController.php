<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;
use app\common\service\AuthService;

class AuthController extends PmsApiController
{
    private $_authService;

    public function __construct(AuthService $authService)
    {
        $this->_authService = $authService;
        return parent::__construct();
    }

    public function ListAction()
    {
        $page = $this->request->post('page');
        $data = $this->_authService->getList([], $page);
        return static::_error(0, '成功', $data['data']);
    }

    public function EditorAction(){
        $data = $this->request->post();
        $ret = $this->_authService->editor($data);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }
}
