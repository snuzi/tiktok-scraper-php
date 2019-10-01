<?php

namespace tests;

use Exception;
use PHPUnit\Framework\TestCase;
use sabri\tiktok\ApiParams;

class ApiParamsTest extends TestCase
{
    public function testGetSessionQueryParamsShouldThrowException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Session query parameters should be set.');
        $apiParams = new ApiParams([]);
        $apiParams->getSessionQueryParams();
    }

    public function testGetQueryParams()
    {
        $sessionParams = [
            'device_id' => '123',
            'iid' => 'abc',
            'openudid' => '5c33af6a989ca7d2'
        ];

        $apiParams = new ApiParams($sessionParams);
        $params = $apiParams->getSessionQueryParams();

        $this->assertEquals($sessionParams, $params);
    }

    public function testGetHeaders()
    {
        $apiParams = new ApiParams([]);
        $defaultHeaders = $apiParams->getHeaders();
        $this->assertLessThanOrEqual(5, count($defaultHeaders));

        $newHeaders = [
            'Host' => 'test.com'
        ];

        $defaultHeaders['Host'] = 'test.com';

        $headers = $apiParams->getHeaders($newHeaders);

        $this->assertEquals($defaultHeaders, $headers);
    }
}
