<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;
use app\common\service\CompanyService;
use app\common\service\MenuService;

class MenuController extends PmsApiController
{
    private $_menuService;

    public function __construct(MenuService $menuService)
    {
        $this->_menuService = $menuService;
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
        $ret = $this->_menuService->getTypeList(true);
        return static::_error($ret['errcode'], $ret['errmsg'], ['list'=>$ret['data']]);
    }

    public function listAction(){

    }
}
