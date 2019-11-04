<?php
// +----------------------------------------------------------------------
// | Title   : 基础类
// +----------------------------------------------------------------------
// | Created : Henrick (me@hejinmin.cn)
// +----------------------------------------------------------------------
// | From    : Shenzhen wepartner network Ltd
// +----------------------------------------------------------------------
// | Date    : 2019-11-04 15:18
// +----------------------------------------------------------------------


namespace app\common\controller;



class PmsApiController extends BaseController
{
    /**
     * 用户ID
     * @var string
     */
    protected $userId = "";
    /**
     * 用户信息
     * @var array
     */
    protected $userInfo = [];

    /**
     * 错误提醒 - json返回
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    static public function _error($code=0, $msg='成功', $data=[]){
        $data = empty($data)? (object)$data:$data;
        return json(['errcode'=> $code, 'errmsg' => $msg, 'data' => $data])->send();
    }


}