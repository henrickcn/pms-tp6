<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * @mixin think\Model
 */
class UserInfoMod extends Model
{
    //表名
    protected $name = "user_info";
    //只读字段
    protected $readonly = ['user_id'];
    //表字段
    protected $schema = [
        'user_id'    => 'string',
        'real_name'  => 'string',
        'phone'      => 'string',
        'email'      => 'string',
        'avatar'     => 'string',
        'nick_name'  => 'string',
        'birth_day'  => 'string',
        'sex'        => 'string',
        'race'       => 'string',
        'card_type'  => 'string',
        'card_num'   => 'string',
        'poli_coun'  => 'string',
        'is_marry'   => 'string',
        'grad_school'=> 'string',
        'education'   => 'string',
        'prof_name'   => 'string',
        'grad_date'   => 'string',
        'house_register'   => 'string',
        'address'          => 'string',
        'join_date'        => 'string',
        'staff_type'       => 'string',
        'contract_start_date' => 'datetime',
        'contract_end_date'   => 'datetime',
        'leave_date'    => 'datetime',
        'tran_date'     => 'datetime',
        'user_status'   => 'string',
        'create_time'   => 'datetime',
        'update_time'   => 'datetime',
    ];
}
