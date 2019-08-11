<?php

namespace App\Services\Interfaces;

/**
 * SerpApiAdapterInterface interface.
 *
 * Interface for making request to Serp Api.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
interface SerpApiAdapterInterface
{
	/**
     * Abstraction to make request to fetch search results from Api.
     *
     * @param string $keyword
     * @param string $url
     * @return array
     */
    public function fetch(string $keyword, string $url) : array;
}