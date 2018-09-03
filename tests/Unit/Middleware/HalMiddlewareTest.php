<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\HalMiddleware;
use App\Http\Middleware\ResourceMiddleware;
use App\Model\AbstractModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class HalMiddlewareTest extends TestCase
{
	public function testSetsDataInJsonResponse()
	{
		$data = ['extracted' => 'data'];
		$model = Mockery::mock(AbstractModel::class);
		$model->shouldReceive('extract')
			->once()
			->andReturns($data);
		$request = Mockery::mock(Request::class);
		$request->shouldReceive('get')
			->with(ResourceMiddleware::class)
			->once()
			->andReturns($model);
		$middleware = new HalMiddleware();
		/** @var JsonResponse $response */
		$response = $middleware->handle($request, function(){});
		$this->assertEquals(200, $response->getStatusCode());
		$json = $response->getData(true);
		$this->assertEquals($data, $json);
	}

	public function testAddsCountToCollectionResponse()
	{
		$data = ['extracted' => 'data'];
		$model = Mockery::mock(AbstractModel::class);
		$model->shouldReceive('extract')
		      ->once()
		      ->andReturns($data);
		$model2 = Mockery::mock(AbstractModel::class);
		$model2->shouldReceive('extract')
		      ->once()
		      ->andReturns($data);
		$request = Mockery::mock(Request::class);
		$request->shouldReceive('get')
		        ->with(ResourceMiddleware::class)
		        ->once()
		        ->andReturns([$model, $model2]);
		$middleware = new HalMiddleware();
		/** @var JsonResponse $response */
		$response = $middleware->handle($request, function(){});
		$this->assertEquals(200, $response->getStatusCode());
		$json = $response->getData(true);
		$this->assertEquals(2, $json['count']);
		$this->assertCount(2, $json['items']);
	}
}
