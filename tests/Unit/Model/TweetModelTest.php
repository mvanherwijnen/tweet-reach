<?php

namespace Tests\Unit\Model;

use App\Model\Tweet\TweetModel;
use App\Model\User\UserModel;
use App\Service\TweetService\TweetServiceInterface;
use Mockery;
use Tests\TestCase;

class TweetModelTest extends TestCase
{
	public function testHydrateModel()
	{
		$user = Mockery::mock(UserModel::class);
		$model = new TweetModel(
			[
				'id' => 1,
				'user' => $user,
				'text' => 'foo'
			]
		);
		$this->assertEquals(1,$model->getId());
		$this->assertEquals($user, $model->getUser());
		$this->assertEquals('foo', $model->getText());
	}

	public function testExtractModel()
	{
		$user = Mockery::mock(UserModel::class);
		$user->shouldReceive('extract')
		     ->once()
		     ->andReturns(['extracted' => 'user']);
		$data = [
			'id' => 1,
			'user' => $user,
			'text' => 'foo'
		];

		$model = new TweetModel(
			$data
		);
		$extraction = $model->extract();
		$this->assertEquals(
			[
				'id' => '1',
				'user' => ['extracted' => 'user'],
				'text' => 'foo',
				'links' => ['retweets' => 'api/tweet/1/retweets']
			], $extraction);
	}

	public function testGetRetweets()
	{
		$user = Mockery::mock(UserModel::class);
		$service = Mockery::mock(TweetServiceInterface::class);
		$model = Mockery::mock(TweetModel::class);
		$model2 = Mockery::mock(TweetModel::class);
		$service->shouldReceive('findRetweetsByTweetId')
			->once()
			->andReturns([
				$model, $model2
			]);
		$model = new TweetModel(
			[
				'id' => 1,
				'user' => $user,
				'text' => 'foo'
			]
		);
		$model->setTweetService($service);
		$model->getRetweets();
		$retweets = $model->getRetweets();
		$this->assertCount(2, $retweets);
	}
}
