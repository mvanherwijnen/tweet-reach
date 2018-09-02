<?php

namespace App\Http\Middleware;

use App\Jobs\WriteToCache;
use App\Service\Cache\CacheAwareInterface;
use App\Service\Cache\CacheAwareTrait;
use App\Service\Config\ConfigAwareInterface;
use App\Service\Config\ConfigAwareTrait;
use Closure;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CacheMiddleware implements CacheAwareInterface, ConfigAwareInterface
{
	use CacheAwareTrait;
	use ConfigAwareTrait;
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $cachedResult = $this->getCache()->get($path);

	    if (!empty($cachedResult)) {
            return new JsonResponse($cachedResult, 200);
        }
        /** @var JsonResponse $response */
        $response = $next($request);
        if ($response->getStatusCode() == 200) {
	        $data = $response->getData(true);
	        //TODO use injected event dispatcher instead of using this magic function for better testability
	        WriteToCache::dispatch($path, $data);
        }

        return $response;
    }

    public function __construct(
    	Cache $cache,
		Config $config
    ) {
    	$this->setCache($cache);
	    $this->setConfig($config);
    }
}
