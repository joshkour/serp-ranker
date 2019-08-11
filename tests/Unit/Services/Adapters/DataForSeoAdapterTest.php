<?php
declare(strict_types = 1);

namespace Tests\Unit\Adapters;

use PHPUnit\Framework\TestCase;
use App\Services\Adapters\DataForSeoAdapter;

/**
 * Defines a class for testing data for seo adapter.
 *
 * @group Unit
 */
final class DataForSeoAdapterTest extends TestCase
{
    /**
     * Adapter under test.
     *
     * @var App\Services\Adapter\DataForSeoAdapter
     */
    private $dataForSeoAdapter;

    /**
     * Mock Client.
     *
     * @var App\Services\External\RestClient
     */
    private $mockClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->mockClient = $this->getMockBuilder('App\Services\External\RestClient')
                                ->disableOriginalConstructor()
                                ->setMethods(['post'])
                                ->getMock();
                                
        $this->dataForSeoAdapter = new DataForSeoAdapter($this->mockClient);
    }

    /**
     * Test method testFetchClientPostData.
     *
     * Test adapter with mock object and its expectations.
     */
    public function testFetchClientPostData() {
        $this->mockClient->expects($this->once())
            ->method('post')
            ->with($this->isType('string'), $this->arrayHasKey('data'))
            ->willReturn([]);

        $this->dataForSeoAdapter->fetch('test keyword', 'test url');
    }
}
