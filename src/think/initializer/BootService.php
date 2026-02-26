<?php

declare(strict_types = 1);

namespace think\initializer;

use think\App;

/**
 * 启动系统服务
 */
class BootService
{
    public function init(App $app)
    {
        $app->boot();
    }
}
