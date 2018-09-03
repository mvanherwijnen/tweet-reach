<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\DomainModelMiddleware;
use App\Model\AbstractModel;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Mockery;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class DomainModelMiddlewareTest extends TestCase
{
    public function testThrowsExceptionOnIncorrectlyConfiguredResource()
    {
	    $this->expectException(\RuntimeException::class);
	    $config = [
	    	'routeName' => [
		        DomainModelMiddleware::class => [
		        	'wrong' => 'config'
		        ]
	        ]
	    ];
		$app = Mockery::mock(Application::class);
		$configRepo = Mockery::mock(Config::class);
		$configRepo->shouldReceive('get')
			->with('resources')
			->once()
			->andReturns($config);
		$route = Mockery::mock(Route::class);
		$route->shouldReceive('getName')
			->andReturns('routeName');
		$request = Mockery::mock(Request::class);
		$request->shouldReceive('route')
			->andReturns($route);
		$middleware = new DomainModelMiddleware($app, $configRepo);
		$middleware->handle($request, function(){});
    }

    public function testSetsDomainModelInRequest()
    {
    	$id = 14;
    	$model = Mockery::mock(AbstractModel::class);
	    $config = [
		    'routeName' => [
			    DomainModelMiddleware::class => [
				    DomainModelMiddleware::REPOSITORY => 'repo',
				    DomainModelMiddleware::METHOD => 'method'
			    ]
		    ]
	    ];
	    $repo = Mockery::mock();
	    $repo->shouldReceive('method')
	         ->with($id)
	         ->andReturn($model);
	    $app = Mockery::mock(Application::class);
	    $app->shouldReceive('make')
		    ->with('repo')
		    ->once()
		    ->andReturns($repo);
	    $configRepo = Mockery::mock(Config::class);
	    $configRepo->shouldReceive('get')
	               ->with('resources')
	               ->andReturns($config);
	    $route = Mockery::mock(Route::class);
	    $route->shouldReceive('getName')
	          ->andReturns('routeName');
	    $request = Mockery::mock(Request::class);
	    $request->shouldReceive('route')
		        ->once()
	            ->andReturns($route);
	    $request->shouldReceive('route')
		    ->once()
		    ->with('id')
		    ->andReturns($id);
	    $attributes = Mockery::mock(ParameterBag::class);
	    $attributes->shouldReceive('set')
		    ->with(DomainModelMiddleware::class, $model)
	        ->once();
	    $request->attributes = $attributes;
	    $middleware = new DomainModelMiddleware($app, $configRepo);
	    $middleware->handle($request, function(){});
    }

	public function testReturns404OnModelNotFound()
	{
		$id = 14;
		$config = [
			'routeName' => [
				DomainModelMiddleware::class => [
					DomainModelMiddleware::REPOSITORY => 'repo',
					DomainModelMiddleware::METHOD => 'method'
				]
			]
		];
		$repo = Mockery::mock();
		$repo->shouldReceive('method')
		     ->with($id)
		     ->andReturn(null);
		$app = Mockery::mock(Application::class);
		$app->shouldReceive('make')
		    ->with('repo')
		    ->once()
		    ->andReturns($repo);
		$configRepo = Mockery::mock(Config::class);
		$configRepo->shouldReceive('get')
		           ->with('resources')
		           ->andReturns($config);
		$route = Mockery::mock(Route::class);
		$route->shouldReceive('getName')
		      ->andReturns('routeName');
		$request = Mockery::mock(Request::class);
		$request->shouldReceive('route')
		        ->once()
		        ->andReturns($route);
		$request->shouldReceive('route')
		        ->once()
		        ->with('id')
		        ->andReturns($id);
		$middleware = new DomainModelMiddleware($app, $configRepo);
		/** @var JsonResponse $response */
		$response = $middleware->handle($request, function(){});
		$this->assertEquals(404, $response->getStatusCode());
	}
}
