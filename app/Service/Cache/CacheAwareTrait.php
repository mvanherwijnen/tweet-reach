<?php

namespace App\Service\Cache;

use Illuminate\Cache\Repository as Cache;

trait CacheAwareTrait
{
    /** @var Cache */
    protected $cache;

    /**
     * @return Cache
     */
    public function getCache(): Cache
    {
        return $this->cache;
    }

    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache): void
    {
        $this->cache = $cache;
    }


}
