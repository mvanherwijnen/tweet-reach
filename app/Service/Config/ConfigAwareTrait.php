<?php

namespace App\Service\Config;

use Illuminate\Config\Repository as Config;

trait ConfigAwareTrait
{
    /** @var Config */
    protected $config;

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }


}
