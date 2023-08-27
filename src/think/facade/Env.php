<?php

namespace think\facade;

use think\Facade;

/**
 * @see \think\Env
 * @mixin \think\Env
 * @method void load(string $file) static 读取环境变量定义文件
 * @method mixed get(string $name = null, mixed $default = null) static 获取环境变量值
 * @method void set(mixed $env, string $value = null) static 设置环境变量值
 */
class Env extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'env';
    }
}
