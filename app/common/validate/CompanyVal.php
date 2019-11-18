<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class CompanyVal extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name' => ['require', 'min:2', 'max:100'],
        'simple_name' => ['require', 'min:2']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require'  => '公司名称不能为空',
        'simple_name.require'  => '公司不能为空',
    ];
}
