<?php

namespace App\Service\Application;

use Illuminate\Contracts\Foundation\Application;

interface ApplicationAwareInterface
{
    public function getApplication(): Application;

    public function setApplication(Application $container);
}
