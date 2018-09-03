<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\DomainModelMiddleware;
use App\Http\Middleware\ResourceMiddleware;
use App\Model\AbstractModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class ResourceMiddlewareTest extends TestCase
{
    public function testSetsModelAsResourceResult()
    {
    	$model = Mockery::mock(AbstractModel::class);
		$request = Mockery::mock(Request::class);
		$request->shouldReceive('get')
			->with(DomainModelMiddleware::class)
			->once()
			->andReturns($model);
		$request->shouldReceive('route')
			->with('relation')
			->once()
			->andReturns(null);
		$attributes = Mockery::mock(ParameterBag::class);
		$attributes->shouldReceive('set')
			->with(ResourceMiddleware::class, $model)
			->once();
		$request->attributes = $attributes;
		$middleware = new ResourceMiddleware();
		$middleware->handle($request, function(){});
    }

    public function testSetsRelationAsResourceResult()
    {
    	$data = ['foo' => 'bar'];
	    $model = Mockery::mock(AbstractModel::class);
	    $model->supportedRelations = ['fooRelation'];
	    $model->shouldReceive('getfooRelation')
		    ->once()
		    ->andReturns($data);
	    $request = Mockery::mock(Request::class);
	    $request->shouldReceive('get')
	            ->with(DomainModelMiddleware::class)
	            ->once()
	            ->andReturns($model);
	    $request->shouldReceive('route')
	            ->with('relation')
	            ->once()
	            ->andReturns('fooRelation');
	    $attributes = Mockery::mock(ParameterBag::class);
	    $attributes->shouldReceive('set')
	               ->with(ResourceMiddleware::class, $data)
	               ->once();
	    $request->attributes = $attributes;
	    $middleware = new ResourceMiddleware();
	    $middleware->handle($request, function(){});
    }

    public function testReturns400OnUnsupportedRelation()
    {
	    $data = ['foo' => 'bar'];
	    $model = Mockery::mock(AbstractModel::class);
	    $model->supportedRelations = ['fooRelation'];
	    $request = Mockery::mock(Request::class);
	    $request->shouldReceive('get')
	            ->with(DomainModelMiddleware::class)
	            ->once()
	            ->andReturns($model);
	    $request->shouldReceive('route')
	            ->with('relation')
	            ->once()
	            ->andReturns('barRelation');
	    $attributes = Mockery::mock(ParameterBag::class);
	    $attributes->shouldNotReceive('set');
	    $request->attributes = $attributes;
	    $middleware = new ResourceMiddleware();
	    /** @var JsonResponse $response */
	    $response = $middleware->handle($request, function(){});
	    $this->assertEquals(400, $response->getStatusCode());
    }
}
