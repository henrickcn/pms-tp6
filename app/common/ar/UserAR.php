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


use app\common\model\UserInfoMod;
use app\common\model\UserMod;
use app\common\tools\GenerateTools;
use think\Db;

class UserAR extends UserMod
{
    public $userStatus = [
        1 => '有效',
        2 => '禁用'
    ];

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
        $user = $this->field('id,phone,oa_name')->where([
            'id' => $data['id']
        ])->where('user_status', 'in', [1,2])->find();
        if($user && (!isset($data['id']) || (isset($data['id']) && $user->id != $data['id']))){
            $msg = $user->phone==$data['phone'] ? '手机号已存在':'OA账号已存在';
            return GenerateTools::error(1, $msg);
        }
        $this->startTrans();
        $userData = [
            'id'      => $data['id']??GenerateTools::uuid(),
            'phone'   => $data['phone'],
            'oa_name' => $data['oa_name'],
            'email'   => $data['email'],
            'user_status' => $data['user_status']
        ];
        if($data['pass_word']){
            $userData['pass_word'] = password_hash($data['pass_word'], PASSWORD_DEFAULT);
        }
        if($user){
            $userRet = static::update($userData);
        }else{
            $userRet = $this->save($userData);
        }

        $userInfoData = [
            'user_id'   => $userData['id'],
            'real_name' => $data['real_name'],
            'email'     => $data['email'],
            'phone'     => $data['phone']
        ];
        $userInfoMod = new UserInfoMod();
        if($user){
            $userInfoRet = $userInfoMod::update($userInfoData, ['user_id'=>$userData['id']]);
        }else{
            $userInfoRet = $userInfoMod->save($userInfoData);
        }
        if($userRet && $userInfoRet){
            $this->commit();
            return true;
        }
        $this->rollback();
        return false;
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

    public function getList($keyword, $page, $orderBy=['create_time' => 'desc']){
        $page['current'] = $page['current']??1;
        $page['size'] = $page['size']??10;
        $where[] = ['user.user_status','in','1,2'];
        if($keyword){
            $where[] = ['info.real_name|user.phone','like', '%'.$keyword.'%'];
        }
        $count = $this->where($where)->join('user_info info', 'info.user_id = user.id')->count();
        $data = $this->field('user.id,info.real_name,user.phone,user.oa_name,info.email,user.login_ip,user.user_status,user.create_time')
            ->alias('user')
            ->join('user_info info', 'info.user_id = user.id')
            ->where($where)->limit(($page['current']-1)*$page['size'], $page['size'])->order($orderBy)
            ->select();
        $page['total'] = $count;
        return [
            'count' => $count,
            'page'  => $page,
            'data'  => $data
        ];
    }

    public function getUserStatusAttr($value){
        return $this->userStatus[$value];
    }

    public function setUserStatusAttr($value){
        return array_keys($this->userStatus,$value)[0];
    }

    public function deleteUserById( $ids=[] ){
        $upUserRet = $this->where('id', 'in', $ids)->update(['user_status' => 3]);
        if($upUserRet){
            return true;
        }
        return false;
    }
}