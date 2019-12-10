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
use app\common\model\MenuMod;
use app\common\tools\GenerateTools;
use think\db\exception\DbException;

class MenuAR extends MenuMod
{

    public function getList($where, $page){
        $where['status'] = 1;
        $count = $this->where($where)->count();
        $data = $this->where($where)->limit(($page['current']-1)*$page['size'],$page['size'])->order('create_time desc')->select();
        $page['total'] = $count;
        return [
            'count' => $count,
            'page'  => $page,
            'data'  => $data
        ];
    }

    public function editor($data){
        $auth = $this->field('id')->whereOR(['id'=>$data['id']])->find();
        try{
            if($auth){
                $auth->setAttrs($data);
                return $auth->updateData();
            }
            unset($data['id']);
            return $this->save($data);
        }catch (DbException $exception){
            return $exception->getMessage();
        }

    }

    public function getMenu($where){
        $where['status'] = 1;
        $count = $this->where($where)->count();
        $data = $this->where($where)->limit(($page['current']-1)*$page['size'],$page['size'])->order('create_time desc')->select();
        $page['total'] = $count;
        return [
            'count' => $count,
            'page'  => $page,
            'data'  => $data
        ];
    }

    public function getListAll($where){
        $where = [
            ['menu.name', 'like', '%'.$where['name'].'%'],
            ['menu.type_id', '=', $where['type_id']],
        ];
        return $this->field("menu.id,menu.icon,menu.name,menu.type_id,menu.auth_id,menu.url,
        menu.parent_id,menu.weight,menu.join_string,menu.level,auth.name auth_name,menu.is_hide")
            ->leftJoin('auth', 'auth.id=menu.auth_id')
            ->where($where)
            ->order('menu.level asc,menu.weight desc')
            ->select()
            ->toArray();

    }
}