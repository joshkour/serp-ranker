<?php
declare(strict_types=1);

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\FileCache;

/**
 * Defines a class for File Cache methods.
 *
 * @group Unit
 */
final class FileCacheTest extends TestCase
{
    /**
     * File cache under test.
     *
     * @var App\Services\FileCache
     */
    private $fileCache;

    /**
     * {@inheritdoc}
     */
    public function setUp() 
    {
        $this->fileCache = new FileCache('/test');
    }

    /**
     * Test method isValidKey.
     *
     * Test that a key is valid.
     */
    public function testIsValidKeyWithValidKey() 
    {
        $key = 'key1';
        $result = $this->fileCache->isValidKey($key);
        $expected = TRUE;

        $this->assertEquals($expected, $result);
    }

    /**
     * Test method isValidKey.
     *
     * Test that a key is invalid.
     */
    public function testIsValidKeyWithInvalidKey() 
    {
        $result = $this->fileCache->isValidKey('');
        $expected = FALSE;

        $this->assertEquals($expected, $result);
    }
}
