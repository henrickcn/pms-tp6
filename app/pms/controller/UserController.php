<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;
use app\common\service\UserService;
use app\common\tools\GenerateTools;

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

    /**
     * 用户列表
     */
    public function listAction(){
        $page = $this->request->post('page');
        $data = $this->request->post('data');
        $data = $this->_userService->getList($data['keyword'], $page, GenerateTools::orderBy($data['orderBy']));
        return static::_error(0, '成功', $data['data']);
    }

    public function editorAction(){
        $user = [
            'id' => $this->request->post('id'),
            'real_name' => $this->request->post('real_name'),
            'pass_word' => $this->request->post('pass_word'),
            'phone' => $this->request->post('phone'),
            'oa_name' => $this->request->post('oa_name'),
            'email' => $this->request->post('email'),
            'user_status' => $this->request->post('user_status'),
        ];
        $ret = $this->_userService->editor($user);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }

    public function delAction(){
        $ids = $this->request->post('id');
        if(empty($ids)){
            return static::_error(1, '至少选择一条数据');
        }
        $idArray = [];
        foreach ($ids as $val){
            $idArray[] = $val['id'];
        }
        $ret = $this->_userService->del($idArray);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }


}
