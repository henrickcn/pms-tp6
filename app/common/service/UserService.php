<?php
// +----------------------------------------------------------------------
// | Title   : 用户功能服务层
// +----------------------------------------------------------------------
// | Created : Henrick (me@hejinmin.cn)
// +----------------------------------------------------------------------
// | From    : Shenzhen wepartner network Ltd
// +----------------------------------------------------------------------
// | Date    : 2019-11-06 15:44
// +----------------------------------------------------------------------


namespace app\common\service;

use app\common\ar\UserAR;
use app\common\tools\GenerateTools;
use app\common\validate\UserVal;
use app\Request;
use think\exception\ValidateException;

class UserService
{
    private $_userAR;

    public function __construct(UserAR $userAR)
    {
        $this->_userAR = $userAR;
    }

    /**
     * 登录服务层
     * @param $data
     * @return array
     */
    public function login($data){
        try{
            $ret = validate(UserVal::class)->scene('login')->check($data);
            if($ret){
                $data['ip'] = request()->ip();
                $uRet = $this->_userAR->login($data);
            }
            if(isset($uRet['errcode'])){
                return $uRet;
            }
            //查询用户信息
            $info = $this->_userAR->getUserInfoById($uRet);
            $session_key = GenerateTools::makeNonceStr(10).base64_encode(password_hash($info['id'].$data['ip'], 1)).GenerateTools::makeNonceStr(10);
            cache($session_key, $info, 3600*2);
            return GenerateTools::error(0, '登陆成功', ['session_key'=>$session_key]);
        }catch (ValidateException $exception){
            return GenerateTools::error(1, $exception->getMessage());
        }
    }

    public function add($data){
        try{
            $data = [
                'phone' => $data['username'],
                'oa_name' => 'johnyhe',
                'pass_word' => $data['pass_word']
            ];
            $vRet = validate(UserVal::class)->scene('insert')->check($data);
            if($vRet === true){
                $userRet = $this->_userAR->editor($data);
            }
            if($userRet === true){
                return GenerateTools::error(0, '添加成功');
            }
            return $userRet;
        }catch (ValidateException $exception){
            return GenerateTools::error(1, $exception->getMessage());
        }
    }

    /**
     * 获取列表
     */
    public function getList($keyword='', $page=[], $orderBy=[]){
        $ret = $this->_userAR->getList($keyword, $page, $orderBy);
        return GenerateTools::error(0, '成功', $ret);
    }

    /**
     * 编辑用户信息
     */
    public function editor( $data = [] ){
        $userVal = new UserVal();
        if(!$userVal->scene('editor')->check($data)){
            return GenerateTools::error(1, $userVal->getError());
        }
        $ret = $this->_userAR->editor($data);
        if($ret){
            return GenerateTools::error(0, '操作成功');
        }
        return GenerateTools::error(1, '操作失败');
    }

    /**
     * 删除用户
     * @param array $ids
     */
    public function del( $ids=[] ){
        $ret = $this->_userAR->deleteUserById( $ids );
        if($ret){
            return GenerateTools::error(0, '删除成功');
        }
        return GenerateTools::error(1, '删除失败');
    }
}