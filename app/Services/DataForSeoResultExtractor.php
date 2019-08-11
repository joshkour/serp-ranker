<?php

namespace App\Services;

use App\Services\Interfaces\SerpResultExtractorInterface;

/**
 * DataForSeoResultExtractor class.
 *
 * Extracts data from results from a Serp Api response.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class DataForSeoResultExtractor implements SerpResultExtractorInterface
{
	/**
     * Holds the results for processing adn extracting.
     *
     * @var array
     */
	private $results;

	/**
     * Class constructor.
     *
     * @param void
     * @return void
     */
	public function __construct() 
	{
    	$this->results = [];
    }

	/**
     * Get the url from a result item.
     *
     * @param array $resultItem
     * @return string
     */
	private function getUrl(array $resultItem) : string {
		$url = '';

		if (isset($resultItem['result_url'])) {
			$url = $resultItem['result_url'];
		}

		return $url;
	}

	/**
     * Get the position from a result item.
     *
     * @param array $resultItem
     * @return int
     */
	private function getPosition(array $resultItem) : int {
		$url = '';

		if (isset($resultItem['result_position'])) {
			$url = $resultItem['result_position'];
		}

		return $url;
	}

	/**
     * DataForSeo return actual results sitting inside ['results' => ['organic' => []]].
     *
     * @param void
     * @return array
     */
	private function getActualResultsArray() : array {
		$results = [];

		if (isset($this->results['results']['organic'])) {
			$results = $this->results['results']['organic'];
		}

		return $results;
	}

	/**
     * Does url match a given url.
     *
     * i.e url1 = 'http://www.creditorwatch.com.au', url2 = 'creditorwatch.com.au' is a match.
     *
     * @param string $url1
     * @param string $url2
     * @return bool
     */
	private function isUrlMatch(string $url1, string $url2) : bool {
		if (strpos($url1, $url2) !== FALSE) {
			return True;
		}

		return False;
	}

	/**
     * Set results for processing.
     *
     * @param array $results
     * @return void
     */
	public function setResults(array $results)  : void
	{
		$this->results = $results;
	}

	/**
     * Extract result items where item url matches given url.
     *
     * Also return ranking position.
     *
     * @param array $results
     * @return void
     */
	public function extractRankByUrl(string $url) : array 
	{
		$ranks = [];

		if (($results = $this->getActualResultsArray())) {
			foreach ($results as $result) {
				$resultUrl = $this->getUrl($result);
				if ($this->isUrlMatch($resultUrl, $url)) {
					$position = $this->getPosition($result);
					$ranks[$position] = $position;
				}
			}
		}
		if (empty($ranks)) {
			$ranks = [0 => 'Not found! (Check SERP Api results stored in cache directory)'];
		}

		return $ranks;
	}
}