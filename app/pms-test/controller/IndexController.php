<?php
namespace app\pms\controller;

use app\pms\common\BaseApiController;

class IndexController extends BaseApiController
{
    public function indexAction()
    {
        echo "hello";
    }

    public function hello(string $name = '')
    {
        return 'hello,' . app('http')->getName();
    }
}
