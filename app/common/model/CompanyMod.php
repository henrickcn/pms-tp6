<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @mixin think\Model
 */
class CompanyMod extends Model
{
    //表名
    protected $name = "company";
    //表字段
    protected $schema = [
        'id'     => 'string',
        'name'   => 'string',
        'simple_name' => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'status' => 'string'
    ];
}
