<?php

namespace Tests\Unit\Model;

use App\Model\User\UserModel;
use Tests\TestCase;

class UserModelTest extends TestCase
{
	public function testHydrateModel()
	{
		$model = new UserModel(
			[
				'id' => '4',
				'name' => 'foo',
				'followers_count' => 34
			]
		);
		$this->assertEquals('4', $model->getId());
		$this->assertEquals('foo', $model->getName());
		$this->assertEquals(34, $model->getFollowersCount());
	}

	public function testExtractModel()
	{
		$data = [
			'id' => '4',
			'name' => 'foo',
			'followers_count' => 34
		];
		$model = new UserModel(
			$data
		);
		$extraction = $model->extract();
		$this->assertEquals($data, $extraction);
	}
}
