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


use app\common\model\MenuTypeMod;
use app\common\tools\GenerateTools;
use think\db\exception\DbException;

class MenuTypeAR extends MenuTypeMod
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
            return $this->save($data);
        }catch (DbException $exception){
            return $exception->getMessage();
        }

    }
}