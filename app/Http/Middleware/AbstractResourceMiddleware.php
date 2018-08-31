<?php

namespace App\Http\Middleware;

use App\Service\Application\ApplicationAwareInterface;
use App\Service\Application\ApplicationAwareTrait;
use Illuminate\Http\Request;

class AbstractResourceMiddleware implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;


}
