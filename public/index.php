<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));

require ROOT_DIR . '/vendor/autoload.php';

use App\Services\FileCache;
use App\Services\SerpApiProxy;
use App\Services\DataForSeoResultExtractor;
use App\Services\DataForSeoResultDao;
use App\Services\Adapters\DataForSeoAdapter;
use App\Facades\SerpFacade;
use App\Services\External\RestClient;
use App\Controllers\SeoRankerController;

// Rest client used to make HTTP request to third party API.
// For the purpose of simple set up I have used the credentials here.
// It should be stored in a .env file and fetched from there.
$client = new RestClient('https://api.dataforseo.com/', null, 'josh.kour@gmail.com', 'UuWnKDt00BkMHHOP');

// Adapter for the real third party API.
$realSerpApi = new DataForSeoAdapter($client);

// Cache class to fetch and save data from API.
// Using File as cache mechanism but can easily be swapped out for redis/memcache.
$cache = new FileCache(ROOT_DIR.'/cache/');

// DAO object for abstraction of data layer.
$serpResultDao = new DataForSeoResultDao($cache);

// Proxy to fetch data from cache if present, other make real API call.
$serpApiProxy = new SerpApiProxy($realSerpApi, $serpResultDao);

// Extract data coming from API.
$serpResultExractor = new DataForSeoResultExtractor();

// Facade for abstraction to the complex sub systems used to fetch SERP results.
$serpFacade = new SerpFacade($serpApiProxy, $serpResultExractor, $serpResultDao);

// A router should be implemented to handle request.
$seoRankerController = new SeoRankerController($serpFacade);
$seoRankerController->index();