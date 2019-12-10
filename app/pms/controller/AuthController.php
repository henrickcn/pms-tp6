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
        $data = $this->request->post('data');
        $data = $this->_authService->getList($data, $page);
        return static::_error(0, '成功', $data['data']);
    }

    public function EditorAction(){
        $data = $this->request->post();
        $ret = $this->_authService->editor($data);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }

    public function delAction(){
        $data = $this->request->post('id');
        if(empty($data)){
            return static::_error(1, '请选择要删除的数据');
        }
        $ids = [];
        foreach ($data as $item){
            $ids[] = $item['id'];
        }
        $ret = $this->_authService->del($ids);
        if($ret){
            return static::_error(0, '删除成功');
        }
        return static::_error(1, '删除失败');
    }
}
