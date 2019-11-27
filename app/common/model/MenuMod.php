<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @mixin think\Model
 */
class MenuMod extends Model
{
    //表名
    protected $name = "menu";
    //表字段
    protected $schema = [
        'id'     => 'number',
        'name'   => 'string',
        'type'   => 'number',
        'auth_id' => 'number',
        'url' => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'level' => 'number',
        'parent_id' => 'string',
        'weight' => 'number'
    ];
}
