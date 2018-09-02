<?php

namespace Tests;

use Illuminate\Cache\Repository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function refreshApplication() {
	    parent::refreshApplication();
	    /** @var Repository $cache */
	    $cache = $this->app->get(Repository::class);
	    $cache->clear();
    }
}
