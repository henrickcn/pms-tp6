<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class UserVal extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'phone'   => ['require|min:6|max:13'],
        'oa_name' => ['require|min:4|max:70'],
        'pass_word' => ['require|min:6|max:100']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'user_name.require' => 'OA账号不能为空',
        'user_name.min'     => 'OA账号不能小于4位',
        'user_name.max'     => 'OA账号不能大于100位',
        'pass_word.require' => '密码不能为空',
        'pass_word.min'     => '密码不能小于6位',
        'pass_word.max'     => '密码不能小于100位'
    ];
}
