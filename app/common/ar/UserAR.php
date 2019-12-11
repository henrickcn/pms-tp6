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


use app\common\model\CompanyUserMod;
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
        $user = $this->field('id,phone,oa_name,pass_word,user_status')->whereRaw(
            'phone=:phone OR oa_name=:oa_name', [
                'phone'   => $data['username'],
                'oa_name' => $data['username']
            ]
        )->where('user_status', 'in', [1,2])->find();
        if(!$user){
            return GenerateTools::error(1, '用户不存在');
        }
        if($user->user_status != '有效'){
            return GenerateTools::error(1, '您的账号已失效，请联系管理员');
        }

        if(!$user->oa_name || !$user->phone){
            return GenerateTools::error(1, '用户名错误');
        }
        if($user->pass_word != password_verify($data['pass_word'], $user->pass_word)){
            return GenerateTools::error(1, '密码错误');
        }
        return $user;
    }

    /**
     * 编辑用户信息
     */
    public function editor($data){
        //查询用户信息是否存在
        $userId = $data['id'];
        $user = $this->field('id,phone,oa_name')->whereRaw(
            'phone=:phone or oa_name=:oa_name', [
                'phone'   => $data['phone'],
                'oa_name' => $data['oa_name']
            ]
        )->where('user_status', 'in', [1,2])->find();
        if($user && (empty($data['id']) || $user->id != $data['id'])){
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
        $compayUserRet = true;
        if($userId){
            $userRet = static::update($userData);
        }else{
            $userRet = $this->save($userData);
            $compayUserMod = new CompanyUserMod();
            $compayUserRet = $compayUserMod->save([
                'user_id'    => $userData['id'],
                'company_id' => $data['company_id'],
            ]);
        }

        $userInfoData = [
            'user_id'   => $userData['id'],
            'real_name' => $data['real_name'],
            'email'     => $data['email'],
            'phone'     => $data['phone']
        ];
        $userInfoMod = new UserInfoMod();
        if($userId){
            $userInfoRet = $userInfoMod::update($userInfoData, ['user_id'=>$userData['id']]);
        }else{
            $userInfoRet = $userInfoMod->save($userInfoData);
        }
        if($userRet && $userInfoRet && $compayUserRet){
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
        return $this->field("user.id,cu.company_id,user.oa_name,user_info.real_name,user_info.avatar,user_info.nick_name,user_info.user_status as info_user_status")
             ->join('user_info', 'user_info.user_id=user.id')
             ->join('company_user cu', 'cu.user_id=user.id')
             ->where([ 'user.id' => $id])
             ->find()->toArray();
    }

    /**
     * 用户列表
     * @param $keyword
     * @param $page
     * @param array $orderBy
     * @return array
     */
    public function getList($keyword, $page, $orderBy=['create_time' => 'desc'], $companyId=''){
        $page['current'] = $page['current']??1;
        $page['size'] = $page['size']??10;
        $where[] = ['user.user_status', 'in', '1,2'];
        if($companyId)
            $where[] = ['cu.company_id', '=', $companyId];
        if($keyword){
            $where[] = ['info.real_name|user.phone','like', '%'.$keyword.'%'];
        }
        $count = $this->where($where)
            ->join('user_info info', 'info.user_id = user.id')
            ->join('company_user cu', 'cu.user_id = user.id')
            ->count();
        $data = $this->field('user.id,info.real_name,user.phone,user.oa_name,info.email,user.login_ip,user.user_status,user.create_time')
            ->alias('user')
            ->join('user_info info', 'info.user_id = user.id')
            ->join('company_user cu', 'cu.user_id = user.id')
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

    /**
     * 删除用户
     * @param array $ids
     * @return bool
     */
    public function deleteUserById( $ids=[] ){
        $upUserRet = $this->where('id', 'in', $ids)->update(['user_status' => 3]);
        if($upUserRet){
            return true;
        }
        return false;
    }

    public function logout( $id, $type ){
        //查询用户信息是否存在
        $user = $this->where([
            'id' => $id
        ])->find();
        $sessionKey = '';
        switch ($type){
            case 'pc':
                $sessionKey = $user->pc_session_key;
                $user->pc_session_key = '';
                break;
            case 'app':
                $sessionKey = $user->app_session_key;
                $user->app_session_key = '';
                break;
            case '':
                $sessionKey = $user->h5_session_key;
                $user->h5_session_key = '';
                break;
        }
        $ret = $user->save();
        if($ret){
            return $sessionKey;
        }
        return false;
    }
}