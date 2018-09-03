<?php

namespace App\Http\Middleware;

use App\Model\AbstractModel;
use Closure;
use Illuminate\Http\JsonResponse;

class ResourceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var AbstractModel $model */
        $model = $request->get(DomainModelMiddleware::class);
        $relation = $request->route('relation');
        if (empty($relation)) {
            $request->attributes->set(ResourceMiddleware::class, $model);
            return $next($request);
        }

        if(!in_array($relation, $model->supportedRelations)) {
            $className = get_class($model);
            return new JsonResponse(['relation' => "$relation not supported by $className"], 400);
        }

        $method = 'get' . $relation;
        $data = $model->$method();
        $request->attributes->set(ResourceMiddleware::class, $data);
        return $next($request);
    }
}
