<?php

namespace Tests\Unit;

use App\Http\Controllers\ApiController;
use ReflectionClass;
use Tests\TestCase;

class ApiControllerCacheTest extends TestCase
{
    protected $cache_paths = [];

    public function testLargeApiResponsesAreStoredInRawFileCache()
    {
        $response = '{"data":"' . str_repeat('x', ApiController::CACHE_LARGE_RESPONSE_THRESHOLD) . '"}';
        $cache_key = 'api:query:test-large-' . uniqid();

        $this->invokeApiCacheMethod('cacheResponse', [$cache_key, $response]);

        $path = $this->invokeApiCacheMethod('largeResponseCachePath', [$cache_key]);
        $this->cache_paths[] = $path;
        $handle = fopen($path, 'rb');
        $expires = intval(trim(fgets($handle)));
        $response_start = fread($handle, 9);
        fclose($handle);

        $this->assertFileExists($path);
        $this->assertGreaterThan(time(), $expires);
        $this->assertSame('{"data":"', $response_start);
        $this->assertSame($response, $this->invokeApiCacheMethod('getCachedResponse', [$cache_key]));
    }

    public function testCompressedCacheResponsesStillDecode()
    {
        if (!function_exists('gzencode') || !function_exists('gzdecode')) {
            $this->markTestSkipped('zlib is required for compressed API cache tests.');
        }

        $response = '{"data":{"ok":true}}';
        $cached_response = ApiController::CACHE_GZIP_PREFIX . gzencode($response, 6);

        $this->assertSame($response, $this->invokeApiCacheMethod('decodeCachedResponse', [$cached_response]));
    }

    public function testRawCacheResponsesStillDecode()
    {
        $response = '{"data":{"ok":true}}';

        $this->assertSame($response, $this->invokeApiCacheMethod('decodeCachedResponse', [$response]));
    }

    protected function tearDown(): void
    {
        foreach ($this->cache_paths as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    protected function invokeApiCacheMethod($method, array $arguments)
    {
        $reflection = new ReflectionClass(ApiController::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $cache_method = $reflection->getMethod($method);
        $cache_method->setAccessible(true);

        return $cache_method->invokeArgs($controller, $arguments);
    }
}
