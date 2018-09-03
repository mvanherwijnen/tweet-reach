<?php

namespace App\Http\Middleware;

use App\Model\AbstractModel;
use Closure;
use Illuminate\Http\JsonResponse;

class HalMiddleware
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
        $responseModel = $request->get(ResourceMiddleware::class);
        $data = [];
        if (is_array($responseModel)) {
            $data['count'] = count($responseModel);
            $items = [];
            /** @var AbstractModel $model */
            foreach($responseModel as $model) {
                $items[] = $model->extract();
            }
            $data['items'] = $items;
        } else {
            $data = $responseModel->extract();
        }
        return new JsonResponse($data, 200);
    }
}
