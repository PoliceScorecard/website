<?php

namespace Tests\Unit;

use App\Http\Controllers\ApiController;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ApiControllerCacheTest extends TestCase
{
    public function testLargeApiResponsesAreCompressedAndDecoded()
    {
        if (!function_exists('gzencode') || !function_exists('gzdecode')) {
            $this->markTestSkipped('zlib is required for compressed API cache tests.');
        }

        $response = '{"data":"' . str_repeat('x', ApiController::CACHE_COMPRESSION_THRESHOLD) . '"}';
        $encoded = $this->invokeApiCacheMethod('encodeCacheResponse', $response);

        $this->assertNotSame($response, $encoded);
        $this->assertStringStartsWith(ApiController::CACHE_GZIP_PREFIX, $encoded);
        $this->assertLessThan(strlen($response), strlen($encoded));
        $this->assertSame($response, $this->invokeApiCacheMethod('decodeCachedResponse', $encoded));
    }

    public function testRawCacheResponsesStillDecode()
    {
        $response = '{"data":{"ok":true}}';

        $this->assertSame($response, $this->invokeApiCacheMethod('decodeCachedResponse', $response));
    }

    protected function invokeApiCacheMethod($method, $argument)
    {
        $reflection = new ReflectionClass(ApiController::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $cache_method = $reflection->getMethod($method);
        $cache_method->setAccessible(true);

        return $cache_method->invoke($controller, $argument);
    }
}
