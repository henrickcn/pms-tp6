<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;
use app\common\service\AuthService;
use app\common\service\CompanyService;
use app\common\service\MenuService;

class MenuController extends PmsApiController
{
    private $_menuService;
    private $_authService;

    public function __construct(MenuService $menuService, AuthService $authService)
    {
        $this->_menuService = $menuService;
        $this->_authService = $authService;
        return parent::__construct();
    }

    public function listTypeAction()
    {
        $page = $this->request->post('page');
        $where = $this->request->post('where');
        $data = $this->_menuService->getTypeList($where['keyword'], $page, $where['orderBy']);
        return static::_error(0, '成功', $data['data']);
    }

    public function editorTypeAction(){
        $data = $this->request->post();
        $data = [
            'id'   => $data['id']??'',
            'name' => $data['name']??'',
            'name_en' => $data['name_en']??''
        ];
        $ret = $this->_menuService->editorType($data);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }

    public function delTypeAction(){
        $data = $this->request->post();
        if(!isset($data['id']) || empty($data['id'])){
            return static::_error(1, '至少选择一条要删除的记录');
        }
        $ids = array_column($data['id'],'id');
        $ret = $this->_menuService->delType($ids);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }

    public function getTypeListAction(){
        $ret = $this->_menuService->getTypeList(true, [], ['prop'=>'create_time', 'orderBy'=>'ascending']);
        return static::_error($ret['errcode'], $ret['errmsg'], ['list'=>$ret['data']]);
    }

    public function listAction(){
        $where = [
            'type_id' => $this->request->post('where.typeId'),
            'name' => $this->request->post('where.keyword'),
        ];
        $ret = $this->_menuService->getListAll($where);
        return static::_error($ret['errcode'], $ret['errmsg'], ['list'=>$ret['data']['list']]);
    }

    public function authListAction(){
        $keyword = $this->request->post('keyword');
        $data = $this->_authService->getList($keyword);
        $authList = [];
        foreach ($data['data']['data'] as $item){
            $authList[] = [
                'value' => $item['name'],
                'address' => $item['name_en'],
                'id' => $item['id'],
            ];
        }
        return static::_error(0, '成功', ['list'=>$authList]);
    }

    public function editorAction(){
        $data = [
            'id' => $this->request->post('id'),
            'name'    => $this->request->post('name'),
            'type_id' => $this->request->post('type_id'),
            'auth_id' => $this->request->post('auth_id'),
            'url'     => $this->request->post('url'),
            'level'     => $this->request->post('level')??1,
            'parent_id' => $this->request->post('parent_id')??0,
            'weight'     => $this->request->post('weight'),
            'icon'       => $this->request->post('icon'),
            'join_string'=> $this->request->post('join_string'),
        ];

        $ret = $this->_menuService->editor($data);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }

    public function delAction(){
        $data = $this->request->post();
        if(!isset($data['id']) || empty($data['id'])){
            return static::_error(1, '至少选择一条要删除的记录');
        }
        $ret = $this->_menuService->del($data['id']);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }
}
