<?php

namespace App\Services;

use App\Services\Adapters\DataForSeoAdapter;
use App\Services\Interfaces\SerpApiAdapterInterface;
use App\Services\Interfaces\SerpResultDaoInterface;
use App\Traits\CustomException;

/**
 * SerpApiProxy class.
 *
 * Proxy class to the real api service.
 *
 * Cache is being used in front of the actual api service.
 *
 * If cache data found, return it, otherwise make a call to the real api service.
 *
 * @link https://docs.dataforseo.com/v2/srp#serp-api
 * @author Josh Kour <josh.kour@gmail.com>
 */
class SerpApiProxy implements SerpApiAdapterInterface
{
    use CustomException;

	/**
     * Adapter to real Serp Api.
     *
     * @var DataForSeoAdapter
     */
	private $realSerpApi;

	/**
     * SERP result dao object.
     *
     * @var SerpResultDaoInterface
     */
	private $serpResultDao;

	/**
     * Class constructor.
     *
     * @param DataForSeoAdapter $realSerpApi
     * @param CacheInterface $cache
     */
	public function __construct(DataForSeoAdapter $realSerpApi, SerpResultDaoInterface $serpResultDao) 
	{
    	$this->realSerpApi = $realSerpApi;
    	$this->serpResultDao = $serpResultDao;
    }

    /**
     * Fetch data from cache or from real SERP Api.
     *
     * @param string $keyword
     * @param string $url
     * @return array
     */
	public function fetch(string $keyword, string $url) : array 
	{
		// Check if we have a cache saved for this keyword and url combination.
		// If present, return cache version instead of making actual request.
		if (!empty($results = $this->serpResultDao->getTodaySerpResult($keyword, $url))) {
			return $results;
		}

		// Make actual request to Serp Api.
		$results = $this->realSerpApi->fetch($keyword, $url);
		
		// Save SERP result.
        if (!$this->serpResultDao->saveTodaySerpResult($keyword, $url, $results)) {
            $this->exception('Error saving SERP result.');
        }

        return $results;
	}
}