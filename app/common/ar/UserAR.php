<?php
// +----------------------------------------------------------------------
// | Title   : 用户数据AR层
// +----------------------------------------------------------------------
// | Created : Henrick (me@hejinmin.cn)
// +----------------------------------------------------------------------
// | From    : Shenzhen wepartner network Ltd
// +----------------------------------------------------------------------
// | Date    : 2019-11-06 15:47
// +----------------------------------------------------------------------


namespace app\common\ar;


use app\common\model\UserMod;
use app\common\tools\GenerateTools;

class UserAR extends UserMod
{
    /**
     * 用户登录系统
     */
    public function login($data){
        //查询用户信息是否存在
        $user = $this->field('id,phone,oa_name,pass_word,user_status')->whereOr([
            'phone'   => $data['username'],
            'oa_name' => $data['username']
        ])->find();
        if(!$user){
            return GenerateTools::error(1, '用户不存在');
        }
        if($user->user_status != 1){
            return GenerateTools::error(1, '您的账号已失效，请联系管理员');
        }

        if(!$user->oa_name || !$user->phone){
            return GenerateTools::error(1, '用户名错误');
        }
        if($user->pass_word != password_verify($data['pass_word'], $user->pass_word)){
            return GenerateTools::error(1, '密码错误');
        }
        $user->login_ip = $data['ip'];
        $user->save();
        return $user->id;
    }

    /**
     * 编辑用户信息
     */
    public function editor($data){
        //查询用户信息是否存在
        $user = $this->field('id,phone,oa_name')->whereOr([
            'phone' => $data['phone'],
            'oa_name' => $data['oa_name']
        ])->find();
        if($user && (!isset($data['id']) || (isset($data['id']) && $user->id != $data['id']))){
            $msg = $user->phone==$data['phone'] ? '手机号已存在':'OA账号已存在';
            return GenerateTools::error(1, $msg);
        }
        $data['id'] = $data['id']??GenerateTools::uuid();
        if($data['pass_word']){
            $data['pass_word'] = password_hash($data['pass_word'], PASSWORD_DEFAULT);
        }

        return $this->save($data);
    }

    /**
     * 根据Id获取用户信息
     * @param string $id
     */
    public function getUserInfoById( $id=0 ){
        return $this->field("user.id,user.oa_name,user_info.real_name,user_info.avatar,user_info.nick_name,user_info.user_status")
             ->join('user_info', 'user_info.user_id=user.id')
             ->where([ 'id' => $id])
             ->find()->toArray();
    }
}