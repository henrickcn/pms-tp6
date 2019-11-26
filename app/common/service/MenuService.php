<?php
// +----------------------------------------------------------------------
// | Title   : 权限功能服务层
// +----------------------------------------------------------------------
// | Created : Henrick (me@hejinmin.cn)
// +----------------------------------------------------------------------
// | From    : Shenzhen wepartner network Ltd
// +----------------------------------------------------------------------
// | Date    : 2019-11-06 15:44
// +----------------------------------------------------------------------


namespace app\common\service;

use app\common\ar\CompanyAR;
use app\common\ar\MenuTypeAR;
use app\common\tools\GenerateTools;
use app\common\validate\CompanyVal;
use app\common\validate\MenuType;
use think\exception\ValidateException;

class MenuService
{
    private $_menuTypeAR;

    public function __construct(MenuTypeAR $menuTypeAR) {
        $this->_menuTypeAR = $menuTypeAR;
    }


    public function getList($where, $page){
        $ret = $this->_menuTypeAR->getList($where, $page);
        return GenerateTools::error(0, '成功', $ret);
    }

    public function editor($data){
        try{
            validate(MenuType::class)->check($data);
            $ret = $this->_companyAR->editor($data);
            if($ret!==true){
                return GenerateTools::error(1, '保存失败'.$ret);
            }
            return GenerateTools::error(0, '保存成功');
        }catch (ValidateException $exception){
            return GenerateTools::error(1, $exception->getMessage());
        }
    }

    public function del($id){
        if(!$id){
            return GenerateTools::error(1, '数据不存在');
        }
        $ret = $this->_companyAR->where(['id'=>$id])->delete();
        if(!$ret){
            return GenerateTools::error(1, '删除失败');
        }
        return GenerateTools::error(0, '删除成功');
    }
}