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


use app\common\model\AuthMod;
use think\db\exception\DbException;

class AuthAR extends AuthMod
{
    public function getNoLoginList(){
        return $this->field('url')->where(['is_login' => 0])->column('url');
    }

    public function getList($keyword, $page, $orderBy=['create_time'=>'desc']){
        $page['current'] = $page['current']??1;
        $page['size'] = $page['size']??10;
        $where = [
            ['name|module_name|url|web_url', 'like', '%'.$keyword.'%']
        ];
        $count = $this->whereOr($where)->count();
        $data  = $this->whereOr($where)->limit(($page['current']-1)*$page['size'],$page['size'])->order($orderBy)->select();
        $page['total'] = $count;
        return [
            'count' => $count,
            'page'  => $page,
            'data'  => $data
        ];
    }

    public function editor($data){
        unset($data['session_key']);
        $auth = $this->field('id')->whereOR(['id'=>$data['id']])->find();
        try{
            if($auth){
                $auth->setAttrs($data);
                return $auth->updateData();
            }
            return $this->save($data);
        }catch (DbException $exception){
            return $exception->getMessage();
        }

    }
}