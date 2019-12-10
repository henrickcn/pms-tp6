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
        'username' => ['require', 'min:4', 'max:100'],
        'pass_word' => ['require', 'min:4', 'max:100'],
        'phone' => ['require', 'mobile'],
        'oa_name' => ['require', 'min:4']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.require'  => '用户名不能为空',
        'username.min'      => '用户名不能小于4位',
        'username.max'      => '用户名不能大于100位',
        'pass_word.require' => '密码不能为空',
        'pass_word.min'     => '密码不能小于6位',
        'pass_word.max'     => '密码不能小于100位',
        'phone.require'     => '手机号不能为空',
        'phone.mobile'      => '手机号格式错误',
        'oa_name.require'   => 'OA账号不能为空',
        'oa_name.min'       => 'OA账号不能小于4位',
        'real_name.require'   => '真实姓名不能为空',
        'email.require'   => '邮箱不能为空',
        'email.email'   => '邮箱格式错误',
    ];

    protected $scene = [
        'login' => ['username', 'pass_word'], //登录场景
        'insert'=> ['phone', 'pass_word', 'oa_name'], //添加场景
        'editor'=> ['real_name','phone','oa_name','email']
    ];
}
