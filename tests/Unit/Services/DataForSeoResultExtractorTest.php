<?php
declare(strict_types=1);

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\DataForSeoResultExtractor;

/**
 * Defines a class for testing extracting data from result return from DataForSeo Api.
 *
 * @group Unit
 */
final class DataForSeoResultExtractorTest extends TestCase
{   
    /**
     * Extractor under test.
     *
     * @var App\Services\DataForSeoResultExtractor
     */
    private $dataForSeoResultExtractor;

    /**
     * {@inheritdoc}
     */
    public function setUp() 
    {
        $this->dataForSeoResultExtractor = new DataForSeoResultExtractor();

        $results = [
            'results' => [
                'organic' => [
                    0 => [
                        'result_url' => 'http://www.creditorwatch.com.au',
                        'result_position' => 1
                    ],
                    1 => [
                        'result_url' => 'http://www.test.com.au',
                        'result_position' => 2
                    ],
                    2 => [
                        'result_url' => 'http://creditorwatch.com.au',
                        'result_position' => 3
                    ],
                    3 => [
                        'result_url' => 'http://www.creditorwatch.com',
                        'result_position' => 4
                    ],
                    4 => [
                        'result_url' => 'test.com.au',
                        'result_position' => 33
                    ],
                ],
            ],
        ];
        $this->dataForSeoResultExtractor->setResults($results);
    }

    /**
     * Test method extractRankByUrl.
     *
     * Test we can extract urls from DataForSeo SERP result. 
     *
     * @dataProvider providerTestExtractRankByUrl
     */
    public function testExtractRankByUrl(string $url, array $expected) 
    {
        $results = $this->dataForSeoResultExtractor->extractRankByUrl($url);
        $this->assertEquals($expected, $results);
    }

    /**
     * Data provider for testExtractRankByUrl().
     *
     * @return array
     *   Test cases.
     */
    public function providerTestExtractRankByUrl()
    {
        return [
            ['creditorwatch.com.au', [1 => 1, 3 => 3,]],
            ['www.creditorwatch.com.au', [1 => 1]],
            ['test.com', [2 => 2, 33 => 33]],
        ];
    }
}
