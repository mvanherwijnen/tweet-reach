<?php

namespace App\Service\Application;

use Illuminate\Contracts\Foundation\Application;

trait ApplicationAwareTrait
{
    /** @var Application */
    protected $application;

    /**
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @param Application $application
     */
    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }


}
