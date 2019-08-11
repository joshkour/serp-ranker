<?php

namespace App\Services;

use App\Services\Interfaces\CacheInterface;
use App\Traits\CustomException;

/**
 * FileCache class.
 *
 * File cache class to handle retrieval and saving of data to file.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class FileCache implements CacheInterface
{
	use CustomException;

	/**
     * Cache path of files.
     *
     * @var string
     */
	private $cachePath;

	/**
     * Class constructor.
     *
     * @param string $cachePath
     * @return void
     */
	public function __construct(string $cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * Get the full path of the cache file given a key.
     *
     * @param string $key
     * @return string
     */
	private function getFullFilePath(string $key) : string
	{
		return $this->cachePath.$key;
	}

	/**
     * Get the cache data given full file path.
     *
     * @param string $filePath
     * @return string
     */
	private function getCacheData(string $filePath) : string
	{
		if (file_exists($filePath)) {
			$results = file_get_contents($filePath);
			return $results;
		}

		return '';
	}

	/**
     * Save the cache data to the full file path.
     *
     * @param string $filePath
     * @param string $value
     * @return bool
     */
	private function saveCacheData(string $filePath, string $value) : bool
	{
		if (file_put_contents($filePath, $value) !== FALSE) {
    		return TRUE;
    	}

    	return FALSE;
	}

	/**
     * Check if a key is valid.
     *
     * @param string $key
     * @return bool
     */
	public function isValidKey(string $key) : bool 
	{
		if (!strlen($key)) {
			return FALSE;
		}

		return TRUE;
	}

	/**
     * Get the cache data for a given key.
     *
     * @param string $key
     */
	public function get(string $key) 
	{
		$filePath = $this->getFullFilePath($key);
        $value = $this->getCacheData($filePath);
		return $value;
	}

	/**
     * Save the data into cache for a given key and value pair.
     *
     * @param string $key
     * @param $value
     * @return bool
     */
    public function save(string $key, $value) : bool 
    {
    	$filePath = $this->getFullFilePath($key);
    	return $this->saveCacheData($filePath, $value);
    }
}