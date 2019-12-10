<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class MenuVal extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name' => ['require', 'min:2', 'max:100'],
        'type_id' => ['require', 'min:2', 'max:100']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require'  => '菜单名称不能为空',
        'type_id.require'  => '菜单分类不能为空',
    ];
}
