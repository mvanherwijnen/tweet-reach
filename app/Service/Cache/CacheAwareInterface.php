<?php

namespace App\Service\Cache;

use Illuminate\Cache\Repository as Cache;

interface CacheAwareInterface
{
    public function getCache(): Cache;

    public function setCache(Cache $container);
}
