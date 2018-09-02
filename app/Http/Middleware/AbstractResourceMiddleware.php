<?php

namespace App\Http\Middleware;

use App\Service\Application\ApplicationAwareInterface;
use App\Service\Application\CacheAwareTrait;
use Illuminate\Http\Request;

class AbstractResourceMiddleware implements ApplicationAwareInterface
{
    use CacheAwareTrait;


}
