<?php

namespace App\Services\Interfaces;

/**
 * SerpResultDaoInterface interface.
 *
 * Interface for fetching data from data store.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
interface SerpResultDaoInterface
{
	/**
     * Save SERP result.
     *
     * @param string $keyword
     * @param string $url
     * @param array $result
     * @return bool
     */
    public function saveTodaySerpResult(string $keyword, string $url, array $result) : bool;

    /**
     * Get today SERP result.
     *
     * @param string $keyword
     * @param string $url
     * @return array
     */
    public function getTodaySerpResult(string $keyword, string $url) : array;
    
	/**
     * Abstraction to get weekly SERP results.
     *
     * @param string $key
     * @param string $url
     * @return array
     */
    public function getWeeklySerpResult(string $key, string $url) : array;
}