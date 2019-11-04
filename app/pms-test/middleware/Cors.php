<?php
declare (strict_types = 1);

namespace app\pms\middleware;

class Cors
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
        //跨域配置
        header('Access-Control-Allow-Origin:'.config('app.cors_domain'));
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:*');
        header('Access-Control-Allow-Credentials:false');
        return $next($request);
    }
}
