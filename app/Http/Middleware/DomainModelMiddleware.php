<?php

namespace App\Http\Middleware;

use App\Service\Application\ApplicationAwareInterface;
use App\Service\Application\ApplicationAwareTrait;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomainModelMiddleware implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    const REPOSITORY = 'repository';
    const METHOD = 'method';

    public function __construct(Application $application)
    {
        $this->setApplication($application);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $resourceConfig = $this->getResourceConfig($request);
        if (!array_key_exists(DomainModelMiddleware::REPOSITORY, $resourceConfig)) {
            $this->throwMisconfiguration($request, 'Repository is missing');
        }
        $app = $this->getApplication();
        $repository = $app->make($resourceConfig['repository']);
        if (!array_key_exists(DomainModelMiddleware::METHOD, $resourceConfig)) {
            $this->throwMisconfiguration($request, 'Method is missing');
        }
        $method = $resourceConfig['method'];
        $id = $request->route('id');

        $model = $repository->$method($id);

        if(empty($model)){
            return new JsonResponse(null, 404);
        }

        $request->request->set(DomainModelMiddleware::class, $model);

        return $next($request);
    }

    protected function getResourceConfig(Request $request)
    {
        $resourcesConfig = config('resources');
        $resourceId = $request->route()->getName();
        return $resourcesConfig[$resourceId][self::class];
    }

    public function throwMisconfiguration(
        Request $request,
        string $reason = null)
    {
        $resourceId = $request->route()->getName();
        $class = get_class($this);

        $message = "Middleware $class is incorrect configured for resource $resourceId.";

        if ($reason) {
            $message .= ' Reason: \'' . $reason . '\'';
        }

        throw new \RuntimeException($message);
    }
}
