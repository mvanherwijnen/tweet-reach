<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CacheMiddleware
{
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
        $cachedResult = Cache::get($path);

        if (!empty($cachedResult)) {
            return new JsonResponse($cachedResult, 200);
        }
        /** @var JsonResponse $response */
        $response = $next($request);
        if ($response->getStatusCode() == 200) {
            $data = $response->getData(true);
            //TODO move to a job for better performance, not needed for json-result
            Cache::put($path, $data, Config::get('cache.minutes_in_cache'));
        }

        return $response;
    }
}
