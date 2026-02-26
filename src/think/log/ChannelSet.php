<?php

declare(strict_types = 1);

namespace think\log;

use think\Log;

/**
 * Class ChannelSet
 * @package think\log
 * @mixin Channel
 */
class ChannelSet
{
    public function __construct(protected Log $log, protected array $channels)
    {
    }

    public function __call($method, $arguments)
    {
        foreach ($this->channels as $channel) {
            $this->log->channel($channel)->{$method}(...$arguments);
        }
    }
}
