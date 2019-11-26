<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @mixin think\Model
 */
class MenuTypeMod extends Model
{
    //表名
    protected $name = "menu_type";
    //表字段
    protected $schema = [
        'id'          => 'number',
        'name'        => 'string',
        'name_en'     => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'status'      => 'number'
    ];
}
