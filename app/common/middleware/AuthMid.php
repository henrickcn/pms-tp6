<?php
declare (strict_types = 1);

namespace app\common\middleware;


use app\common\controller\PmsApiController;
use think\Response;

class AuthMid
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $sessionKey = $request->header('session_key');
        if(!$sessionKey){
            PmsApiController::_error(100, '你还没有登录哦~');
        }
        PmsApiController::_error();
        return $next($request);
    }
}
