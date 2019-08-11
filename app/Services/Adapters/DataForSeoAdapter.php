<?php

namespace App\Services\Adapters;

use App\Services\Interfaces\SerpApiAdapterInterface;
use App\Services\External\RestClient;
use App\Traits\CustomException;

/**
 * DataForSeoAdapter class.
 *
 * Adapter for DataForSEO Serp API.
 *
 * @link https://docs.dataforseo.com/v2/srp#serp-api
 * @author Josh Kour <josh.kour@gmail.com>
 */
class DataForSeoAdapter implements SerpApiAdapterInterface
{
	use CustomException;

	/**
     * Rest client provided by api.
     *
     * @var RestClient
     */
	private $client;

	/**
     * Api path for serp api.
     *
     * @var string
     */
	protected const SRP_API_PATH = 'v2/live/srp_tasks_post';

	/**
     * Search engine id.
     *
     * Optional field for api.
     *
     * i.e 51 = 'google.com.au'
     *
     * @var int
     */
	protected const SE_ID = 51;

	/**
     * Search engine location id.
     *
     * Optional field for api.
     *
     * i.e 1000286 = 'Sydney Australia'
     *
     * @var int
     */
	protected const LOC_ID = 1000286;

	/**
     * Search engine language.
     *
     * Required field for api.
     *
     * @var string
     */
	protected const SE_LANGUAGE = 'English';

	/**
     * Class constructor.
     *
     * @param RestClient $client
     * @return void
     */
	public function __construct(RestClient $client) 
	{
    	$this->client = $client;
    }

    /**
     * Prepare post data for the api request.
     *
     * @param string $keyword
     * @return array
     */
    private function preparePostData(string $keyword) : array 
    {
    	$return = [];
    	$taskId = mt_rand(0,30000000);
    	$return[$taskId] = array(
		    'se_id' => self::SE_ID,
		    'loc_id' => self::LOC_ID,
		    'se_language' => self::SE_LANGUAGE,
		    'key' =>  mb_convert_encoding($keyword, 'UTF-8'),
		);

		return $return;
    }

    /**
     * Make request to fetch search results from api.
     *
     * @throws Exception
     * @param string $keyword
     * @param string $url
     * @return array
     */
	public function fetch(string $keyword, string $url) : array 
	{
		$results = [];
		$data = ['data' => $this->preparePostData($keyword)];

	    try {
	        $results = $this->client->post(self::SRP_API_PATH, $data);
	    } catch (RestClientException $e) {
	    	$this->exception($e->getMessage());
	    }

	    return $results;
	}
}
