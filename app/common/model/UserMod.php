<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 用户模型
 * @mixin think\Model
 */
class UserMod extends Model
{
    //表名
    protected $name = "user";
    //只读字段
    protected $readonly = ['id'];
    //表字段
    protected $schema = [
        'id'      => 'string',
        'phone'   => 'string',
        'oa_name' => 'string',
        'pass_word' => 'string',
        'pc_session_key' => 'string',
        'app_session_key' => 'string',
        'h5_session_key' => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'login_ip' => 'string',
        'user_status' => 'int',
    ];

    public function getNickNameAttr($value, $data){
        if($value){
            return $value;
        }
        if($data['real_name']){
            return $data['real_name'];
        }
        if($data['oa_name']){
            return $data['oa_name'];
        }
    }

    public function getAvatarAttr($value, $data){
        if($value){
            return $value;
        }
        return config('app.default_avatar');
    }
}
