<?php

namespace App\Services\Interfaces;

/**
 * SerpResultExtractorInterface interface.
 *
 * Interface for extracting results from Serp Api response.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
interface SerpResultExtractorInterface
{
	/**
     * Abstraction to Set the results from Serp Api response.
     *
     * @param array $results
     * @return void
     */
	public function setResults(array $results) : void;

	/**
     * Abstraction to extract result items where item url matches given url.
     *
     * Also return ranking position.
     *
     * @param array $url
     * @return array
     */
    public function extractRankByUrl(string $url) : array;
}