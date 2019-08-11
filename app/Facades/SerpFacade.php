<?php

namespace App\Facades;

use App\Services\Interfaces\SerpApiAdapterInterface;
use App\Services\Interfaces\SerpResultExtractorInterface;
use App\Services\Interfaces\SerpResultDaoInterface;
use App\Traits\CustomException;

/**
 * SerpFacade class.
 *
 * Facade for making request to fetch Search engine result pages (SERP) and extract the data.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class SerpFacade
{
    private $serpApiProxy;
    private $serpResultExractor;
    private $serpResultDao;

	/**
     * Class constructor.
     *
     * @param SerpApiAdapterInterface $serpApiProxy
     * @param SerpResultExtractorInterface $serpResultExractor
     * @param SerpResultDaoInterface $serpResultDao
     * @param string $keyword
     * @param string $url
     * @return void
     */
	public function __construct(SerpApiAdapterInterface $serpApiProxy, SerpResultExtractorInterface $serpResultExractor, SerpResultDaoInterface $serpResultDao)
    {   
        $this->serpApiProxy = $serpApiProxy;
        $this->serpResultExractor = $serpResultExractor;
        $this->serpResultDao = $serpResultDao;
    }

    /**
     * Get ranking position of a url given a keyword in SERP.
     *
     * @param string $keyword
     * @param string $url
     * @return array
     */
    public function getRankByKeywordMatchingUrl(string $keyword, string $url) : array 
    {
        $ranksByDate = [];

        // Fetch SERP results given keyword and url for today and extract the positions.
        $results = $this->serpApiProxy->fetch($keyword, $url);
        $resultsToday[date('Y-m-d')] = $results;

        // Grab historical (7 day) SERP results and extract the positions.
        $resultsWeeklyTmp = $this->serpResultDao->getWeeklySerpResult($keyword, $url);
        $results = array_merge($resultsToday, $resultsWeeklyTmp);
        
        foreach ($results as $date => $result) {
            $this->serpResultExractor->setResults($result);
            $rankPosition = $this->serpResultExractor->extractRankByUrl($url);
            $ranksByDate[$date] = $rankPosition;
        }

        // Sort the results by the dates.
        krsort($ranksByDate);

        return $ranksByDate;
    }
}