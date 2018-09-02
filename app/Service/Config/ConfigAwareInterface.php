<?php

namespace App\Service\Config;

use Illuminate\Config\Repository as Config;

interface ConfigAwareInterface
{
    public function getConfig(): Config;

    public function setConfig(Config $container);
}
