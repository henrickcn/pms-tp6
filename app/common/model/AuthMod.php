<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @mixin think\Model
 */
class AuthMod extends Model
{
    //表名
    protected $name = "auth";
    //只读字段
    protected $readonly = ['id'];
    //表字段
    protected $schema = [
        'id'      => 'string',
        'name'   => 'string',
        'url' => 'string',
        'param' => 'string',
        'is_login' => 'string',
        'is_sys' => 'string',
        'module_name' => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
