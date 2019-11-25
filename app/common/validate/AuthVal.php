<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class AuthVal extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'id' => ['require', 'min:4', 'max:100'],
        'name' => ['require', 'min:2', 'max:100'],
        'url' => ['require', 'min:4'],
        'web_url' => ['require', 'min:4'],
        'is_login' => ['require'],
        'is_sys' => ['require'],
        'module_name' => ['require']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'id.require'  => '权限Id不能为空',
        'name.require'  => '权限名称不能为空',
        'url.require'  => '权限链接不能为空',
        'web_url.require'  => '前端链接不能为空',
        'is_login.require'  => '是否登录不能为空',
        'is_sys.require'  => '是否系统权限不能为空',
        'module_name.require'  => '模块名称不能为空',
    ];
}
