<?php
declare(strict_types = 1);

namespace Tests\Unit\Integration;

use PHPUnit\Framework\TestCase;
use App\Services\Adapters\DataForSeoAdapter;
use App\Services\External\RestClient;

/**
 * Defines a class for testing actual API Service with DataForSeo Adapter.
 *
 * @group Unit
 */
final class DataForSeoAdapterTest extends TestCase
{   
    /**
     * Test method fetch.
     *
     * Test fetch with invalid credentials.
     */
    public function testFetchInvalidCredentials() {
        // Assert expecting exception to be thrown with invalid credentials
        $this->expectException(\Exception::class);

        // Fake invalid credentials
        $apiUrl = 'https://api.dataforseo.com/';
        $emailAddress = 'invalid@gmail.com';
        $password = 'ASDYKJNK123';
        $client = new RestClient($apiUrl, null, $emailAddress, $password);

        $dataForSeoAdapter = new DataForSeoAdapter($client);
        $dataForSeoAdapter->fetch('creditorwatch', 'creditorwatch.com.au');
    }

    /**
     * Test method fetch.
     *
     * Test fetch with valid credentials and responds successfuly with response payload.
     */
    public function testFetchSuccessful() {

        // These credentials should be stored in .env file
        $apiUrl = 'https://api.dataforseo.com/';
        $emailAddress = 'josh.kour@gmail.com';
        $password = 'UuWnKDt00BkMHHOP';
        $client = new RestClient($apiUrl, null, $emailAddress, $password);

        $dataForSeoAdapter = new DataForSeoAdapter($client);
        $results = $dataForSeoAdapter->fetch('creditorwatch', 'creditorwatch.com.au');

        // Test top level keys present
        $this->assertArrayHasKey('status', $results);
        $this->assertArrayHasKey('results', $results);

        // Test 'organic' key presents in actual results array
        $results = $results['results'];
        $this->assertArrayHasKey('organic', $results);
    }
}
