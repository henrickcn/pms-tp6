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

use app\common\ar\AuthAR;
use app\common\tools\GenerateTools;
use app\common\validate\AuthVal;
use think\exception\ValidateException;

class AuthService
{
    private $_authAR;

    public function __construct(AuthAR $authAR) {
        $this->_authAR = $authAR;
    }

    /**
     * 获取不用验证的url
     */
    public function getNoCheckUrl(){
        $data = cache(GenerateTools::cacheName('noCheckAuthList'));
        if(!$data){
            //缓存无需验证登录的url
            $data = $this->_authAR->getNoLoginList();
            cache(GenerateTools::cacheName('noCheckAuthList'), $data);
        }
        return $data;
    }

    public function getList($data=[], $page=[]){
        $orderBy = '';
        if(is_array($data)){
            $keyword = $data['keyword'];
            $orderBy = GenerateTools::orderBy($data['orderBy']);
        }else{
            $keyword = $data;
        }

        $ret = $this->_authAR->getList($keyword, $page, $orderBy);
        return GenerateTools::error(0, '成功', $ret);
    }

    public function editor($data){
        try{
            validate(AuthVal::class)->check($data);
            $ret = $this->_authAR->editor($data);
            if($ret!==true){
                return GenerateTools::error(1, '保存失败'.$ret);
            }
            return GenerateTools::error(0, '保存成功');
        }catch (ValidateException $exception){
            return GenerateTools::error(1, $exception->getMessage());
        }
    }

    public function del($ids){
        return $this->_authAR->where('id', 'in', $ids)->delete($ids);
    }
}