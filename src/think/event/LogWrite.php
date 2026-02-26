<?php

declare(strict_types = 1);

namespace think\event;

/**
 * LogWrite事件类
 */
class LogWrite
{
    public function __construct(public string $channel, public array $log)
    {
    }
}
