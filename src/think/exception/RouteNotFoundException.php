<?php

namespace think\exception;

class RouteNotFoundException extends HttpException
{

    public function __construct()
    {
        parent::__construct(404, '当前访问路由未定义或不匹配');
    }

}