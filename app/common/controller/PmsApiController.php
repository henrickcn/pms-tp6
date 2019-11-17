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



use think\App;

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

    private $_authService = '';

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


    public function __construct() {
        $this->_authService = invoke('app\common\service\AuthService');
        return parent::__construct();
    }

    public function initialize()
    {
        $data = $this->_authService->getNoCheckUrl();
        $url = $this->request->baseUrl();
        if(in_array($url, $data)){
            return parent::initialize();
        }
        $sessionKey = $this->request->header('Authorization');
        if(!$sessionKey){
            exit(static::_error(100, '你还没有登录哦~'));
        }
        $this->userInfo = cache($sessionKey);
        cache($sessionKey, $this->userInfo, 7200);
        return parent::initialize(); // TODO: Change the autogenerated stub
    }
}