<?php

namespace App\Services;

use App\Services\Interfaces\SerpResultDaoInterface;
use App\Services\Interfaces\CacheInterface;

/**
 * DataForSeoResultDao class.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class DataForSeoResultDao implements SerpResultDaoInterface
{
	private $cache;

	/**
     * Class constructor.
     *
     * @param CacheInterface $cache
     * @return void
     */
	public function __construct(CacheInterface $cache) 
	{
    	$this->cache = $cache;
    }

    private function generateFullKey(string $keyword, string $url, string $date) : string {
        return $this->formatKeywordForCache($keyword).'_'.$url.'_'.$date;
    }

    private function formatKeywordForCache(string $keyword) : string {
        return str_replace(' ', '-', $keyword);
    }

    private function getTodayDate() : string {
        return date('Y-m-d');
    }

    private function getWeekAgoDate() : string{
        $weekAgo = strtotime('-1 week');
        $weekAgoDate = date('Y-m-d', $weekAgo);
        return $weekAgoDate;
    }

    /**
     * Save SERP result.
     *
     * @param string $keyword
     * @param string $url
     * @param array $result
     * @return bool
     */
    public function saveTodaySerpResult(string $keyword, string $url, array $result) : bool 
    {
        // Serialize the data for cache and save.
        $todayDate = $this->getTodayDate();
        $key = $this->generateFullKey($keyword, $url, $todayDate);
        
        $result = serialize($result);
        if ($this->cache->save($key, $result)) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get today SERP result.
     *
     * @param string $keyword
     * @param string $url
     * @return array
     */
    public function getTodaySerpResult(string $keyword, string $url) : array 
    {
        $todayDate = $this->getTodayDate();
        $key = $this->generateFullKey($keyword, $url, $todayDate);
        if (($results = $this->cache->get($key)) !== '') {
            return unserialize($results);
        }

        return [];
    }


	/**
     * Get weekly SERP results.
     *
     * @param string $keyword
     * @param string $url
     * @return array
     */
    public function getWeeklySerpResult(string $keyword, string $url) : array 
    {
    	$results = [];

    	$weekAgoDate = $this->getWeekAgoDate();
    	for ($i = 0; $i < 7; $i ++) {
    		$date = date('Y-m-d', strtotime($weekAgoDate . ' +'.$i.' day'));
    		$key = $this->generateFullKey($keyword, $url, $date);
	    	if (($result = $this->cache->get($key)) !== '') {
				$result = unserialize($result);
				$results[$date] = $result;
			}
		}

		return $results;
    }
}