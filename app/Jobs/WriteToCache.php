<?php

namespace App\Jobs;

use App\Service\Cache\CacheAwareInterface;
use App\Service\Cache\CacheAwareTrait;
use App\Service\Config\ConfigAwareInterface;
use App\Service\Config\ConfigAwareTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Config\Repository as Config;
use Illuminate\Cache\Repository as Cache;


class WriteToCache implements
	ShouldQueue,
	ConfigAwareInterface,
	CacheAwareInterface
{
    use Dispatchable,
	    InteractsWithQueue,
	    Queueable,
	    SerializesModels,
	    ConfigAwareTrait,
	    CacheAwareTrait;

    /** @var string */
    protected $requestPath;

    /** @var array */
    protected $data;

    public function __construct(
    	string $requestPath,
    	array $data
    ){
    	$this->setRequestPath($requestPath);
    	$this->setData($data);
    }

    public function handle(
    	Config $config,
		Cache $cache
	){
	    $minutesInCache = $config->get('cache.minutes_in_cache');
	    $cache->set(
	    	$this->getRequestPath(),
		    $this->getData(),
		    $minutesInCache
	    );
    }

	/**
	 * @return string
	 */
	public function getRequestPath(): string {
		return $this->requestPath;
	}

	/**
	 * @param string $requestPath
	 */
	public function setRequestPath( string $requestPath ): void {
		$this->requestPath = $requestPath;
	}

	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData( array $data ): void {
		$this->data = $data;
	}


}
