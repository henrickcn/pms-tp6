<?php
declare (strict_types = 1);

namespace app\pms\controller;

use app\common\controller\PmsApiController;
use app\common\service\CompanyService;

class CompanyController extends PmsApiController
{
    private $_companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->_companyService = $companyService;
        return parent::__construct();
    }

    public function ListAction()
    {
        $page = $this->request->post('page');
        $data = $this->_companyService->getList([], $page);
        return static::_error(0, '成功', $data['data']);
    }

    public function EditorAction(){
        $data = $this->request->post();
        $ret = $this->_companyService->editor($data);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }

    public function DelAction(){
        $data = $this->request->post();
        $ret = $this->_companyService->del($data['id']);
        return static::_error($ret['errcode'], $ret['errmsg']);
    }
}
