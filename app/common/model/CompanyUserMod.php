<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @mixin think\Model
 */
class CompanyUserMod extends Model
{
    //表名
    protected $name = "company_user";
    //表字段
    protected $schema = [
        'user_id'     => 'string',
        'company_id'   => 'string'
    ];
}
