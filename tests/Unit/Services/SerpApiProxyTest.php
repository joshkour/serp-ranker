<?php
declare(strict_types=1);

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\Adapters\DataForSeoAdapter;
use App\Services\SerpApiProxy;
use App\Services\DataForSeoResultDao;

/**
 * Defines a class for testing SERP Api proxy is behaving properly before making actual request with real service.
 *
 * @group Unit
 */
final class SerpApiProxyTest extends TestCase
{
    /**
     * SERP Api Prox under test.
     *
     * @var App\Services\SerpApiProxy
     */
    private $serpApiProxy;

    /**
     * Mock real SERP Api.
     *
     * @var App\Services\Adapters\DataForSeoAdapter
     */
    private $mockRealSerpApi;

    /**
     * Mock SERP result DAO.
     *
     * @var App\Services\DataForSeoResultDao
     */
    private $mockSerpResultDao;

    /**
     * {@inheritdoc}
     */
    public function setUp () 
    {
        // Mock real serp api object (DataForSeoAdapter).
        $this->mockRealSerpApi = $this->getMockBuilder('App\Services\Adapters\DataForSeoAdapter')
            ->disableOriginalConstructor()
            ->setMethods(['fetch'])
            ->getMock();

        // Mock SerpResultDao.
        $this->mockSerpResultDao = $this->getMockBuilder('App\Services\DataForSeoResultDao')
            ->disableOriginalConstructor()
            ->setMethods(['getTodaySerpResult', 'saveTodaySerpResult'])
            ->getMock();

        $this->serpApiProxy = new SerpApiProxy($this->mockRealSerpApi, $this->mockSerpResultDao);
    }

    /**
     * Test method fetch.
     *
     * Test fetch has found a cache and returns the result without making call to real SERP api.
     */
    public function testFetchCacheHit() 
    {
        $keyword = 'test keyword';
        $url = 'test.com.au';
        $resultSerp = ['status' => 'ok'];

        // Assert that 'get' method is called once and argument is a string.
        $this->mockSerpResultDao->expects($this->once())
            ->method('getTodaySerpResult')
            ->with($this->isType('string'), $this->isType('string'))
            ->willReturn($resultSerp);

        // Make sure we do not make a call to real serp api.
        $this->mockRealSerpApi->expects($this->never())
            ->method('fetch');

        $results = $this->serpApiProxy->fetch($keyword, $url);

        // Assert that the end results equals our expectations from the cache.
        $this->assertEquals($results, $resultSerp);
    }

    /**
     * Test method fetch.
     *
     * Test fetch without cache found which makes a call to real SERP api and save cache.
     */
    public function testFetchCacheMiss() 
    {
        $keyword = 'test keyword';
        $url = 'test.com.au';
        $resultSerp = ['status' => 'ok'];

        // 'get' method is called once with cache not found.
        $this->mockSerpResultDao->expects($this->once())
            ->method('getTodaySerpResult')
            ->with($this->isType('string'), $this->isType('string'))
            ->willReturn([]);

        // Since cache is not found, we make a call to real serp api.
        $this->mockRealSerpApi->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($keyword), $this->equalTo($url))
            ->willReturn($resultSerp);

        // make sure we make a call to save the data.
        $this->mockSerpResultDao->expects($this->once())
            ->method('saveTodaySerpResult')
            ->with($this->equalTo($keyword), $this->equalTo($url), $this->equalTo($resultSerp))
            ->willReturn(TRUE);

        $results = $this->serpApiProxy->fetch($keyword, $url);

        // Assert that the expected end results equal that of the serp api.
        $this->assertEquals($results, $resultSerp);
    }
}
