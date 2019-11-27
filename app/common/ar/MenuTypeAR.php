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

    public function getList($condition=[], $page=[], $orderBy=[]){
        $where['status'] = 1;
        $whereOR = [];
        if($condition){
            $whereOR[] = ['name|name_en','like',"%".$condition."%"];
        }
        $orderByString = 'create_time desc';
        if(!empty($orderBy)){
            $orderByString = $orderBy['prop']." ".(isset($orderBy['orderBy']) && $orderBy['orderBy']=='ascending' ?"asc":"desc");
        }
        $count = $this->where($where)->where($whereOR)->count();
        $this->where($where)->where($whereOR);
        if(is_array($condition)){
            $this->limit(($page['current']-1)*$page['size'],$page['size']);
        }
        $data = $this->order($orderByString)->select();
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
}