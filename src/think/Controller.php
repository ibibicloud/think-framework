<?php

namespace think;

class Controller
{
    // 控制器中间件
    protected $middleware = [];

    // 构造方法
    public function __construct(App $app = null)
    {
        $this->app = $app ?: Container::get('app');
        $this->initialize();
        $this->registerMiddleware();
    }

    // 控制器初始化
    protected function initialize()
    {}

    // 注册控制器中间件
    public function registerMiddleware()
    {
        foreach ( $this->middleware as $key => $val ) {
            if ( !is_int($key) ) {
                $only = $except = null;
                if ( isset($val['only']) ) {
                    $only = array_map(function ($item) {
                        return strtolower($item);
                    }, $val['only']);
                } elseif ( isset($val['except']) ) {
                    $except = array_map(function ($item) {
                        return strtolower($item);
                    }, $val['except']);
                }
                if ( isset($only) && !in_array(request()->action(), $only) ) {
                    continue;
                } elseif ( isset($except) && in_array(request()->action(), $except) ) {
                    continue;
                } else {
                    $val = $key;
                }
            }
            $this->app['middleware']->controller($val);
        }
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app'], $data['request']);
        return $data;
    }

}