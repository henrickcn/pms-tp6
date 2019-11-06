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
}