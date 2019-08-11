<?php
declare(strict_types=1);

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\DataForSeoResultDao;
use App\Services\FileCache;

/**
 * Defines a class for testing DataForSeo DAO.
 *
 * @group Unit
 */
final class DataForSeoResultDaoTest extends TestCase
{   
    /**
     * DataForSeoResult dao under test.
     *
     * @var App\Services\SerpApiProxy
     */
    private $dataForSeoResultDao;

    /**
     * Mock cache.
     *
     * @var App\Services\FileCache
     */
    private $mockCache;

    /**
     * {@inheritdoc}
     */
    public function setUp() 
    {
        $this->mockCache = $this->getMockBuilder('App\Services\FileCache')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'save'])
            ->getMock();

        $this->dataForSeoResultDao = new DataForSeoResultDao($this->mockCache);
    }

    /**
     * Test method getTodaySerpResult.
     *
     * Test today SERP result found.
     */
    public function testGetTodaySerpResultFound() 
    {
        $keyword = 'testkeyword';
        $url = 'http://www.test.com.au';
        $resultArray = ['status' => 'ok'];
        $serializeData = serialize($resultArray);
        $expected = $resultArray;

        // Assert that 'get' method is called once and argument is a string and will return a serialize data.
        $this->mockCache->expects($this->once())
            ->method('get')
            ->with($this->isType('string'))
            ->willReturn($serializeData);

        $results = $this->dataForSeoResultDao->getTodaySerpResult($keyword, $url);
        $this->assertEquals($expected, $results);
    }

    /**
     * Test method getTodaySerpResult.
     *
     * Test today SERP result not found.
     */
    public function testGetTodaySerpResultNotFound() 
    {
        $keyword = 'testkeyword';
        $url = 'http://www.test.com.au';
        $resultArray = ['status' => 'ok'];
        $serializeData = serialize($resultArray);
        $expected = [];

        // Assert that 'get' method is called once and argument is a string and will return a serialize data.
        $this->mockCache->expects($this->once())
            ->method('get')
            ->with($this->isType('string'))
            ->willReturn('');

        $results = $this->dataForSeoResultDao->getTodaySerpResult($keyword, $url);
        $this->assertEquals($expected, $results);
    }
}
