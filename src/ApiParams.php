<?php

namespace sabri\tiktok;

use Exception;
use GuzzleHttp\Client;

/**
 * This class manages Tiktok API query parameters
 */
class ApiParams
{

    /** @var array default query parameters */
    private $_baseQueryParameters = [
        'carrier' => 'spusu',
        'mcc_mnc' => '23205',
        'ad_area' => '1080x1848',
        'sdk_version' => '19800',
        'os_api' => '26',
        'display_density' => '1080x1920',
        'dpi' => '480',
        'bh' => '368',
        'display_dpi' => '480',
        'density' => '3.0',
        'ac' => 'wifi',
        'channel' => 'googleplay',
        'version_code' => '-1',
        'user_period' => '0',
        'user_mode' => '-1',
        'refresh_num' => '5',
        'user_id' => '0',
        'gaid' => '8ace5d21-2166-4c1b-9e15-6890cf252fae',
        'android_id' => '5c33af6a989ca7d2',
        'ad_user_agent' => 'Mozilla/5.0 (Linux; Android 8.0.0; MI 5 Build/OPR1.170623.032; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.73 Mobile Safari/537.36',
        'manifest_version_code' => '2019091703',
        'current_region' => 'AT',
        'app_language' => 'en',
        'app_type' => 'normal',
        'device_type' => 'MI+5',
        'language' => 'en',
        'locale' => 'en',
        'resolution' => '1080*1920',
        'update_version_code' => '-1',
        'ac2' => 'wifi',
        'sys_region' => 'GB',
        'uoo' => '0',
        'is_my_cn' => '0',
        'timezone_name' => 'Europe%2FVienna',
        'residence' => 'AT',
        'carrier_region' => 'AT',
        'device_id' => '6737916265103345157',
        'pass-route' => '1',
        'os_version' => '8.0.0',
        'timezone_offset' => '3600',
        'carrier_region_v2' => '232',
        'app_name' => 'musical_ly',
        'ab_version' => '13.1.2',
        'version_name' => '13.1.2',
        'device_brand' => 'Xiaomi',
        'ssmix' => 'a',
        'pass-region' => '1',
        'device_platform' => 'android',
        'build_number' => '13.1.2',
        'region' => 'GB',
        'aid' => '1233',
        'ts' => '1568890050',
        '_rticket' => '1568890049792'
    ];

    /** @var array default headers */
    private $_baseHeaders = [
        "Host" => 'api2.musical.ly',
        'X-SS-TC' => "0",
        'User-Agent' => "com.zhiliaoapp.musically/2018090613 (Linux; U; Android 8.0.0; tr_TR; TA-1020; Build/O00623; Cronet/58.0.2991.0)",
        'Accept-Encoding' => "gzip",
        'Connection' => "keep-alive"
    ];

    /** @var array extra query parameters related to the device */
    private $_sessionQueryParams = [];

    public function __construct($sessionQueryParams)
    {
        $this->_sessionQueryParams = $sessionQueryParams;
    }

    public function getQueryParams(array $extraParams = []): array
    {
        $this->_baseQueryParameters['_rticket'] = time() * 1000;
        $this->_baseQueryParameters['ts'] = time() * 1000;

        return array_replace_recursive(
            $this->_baseQueryParameters,
            $extraParams,
            $this->getSessionQueryParams()
        );
    }

    public function getSessionQueryParams(): array
    {
        if (!$this->_sessionQueryParams) {
            throw new Exception('Session query parameters should be set.');
        }

        return $this->_sessionQueryParams;
    }

    public function setSessionQueryParams(array $sessionQueryParams): self
    {
        $this->_sessionQueryParams = $sessionQueryParams;

        return $this;
    }

    public function getHeaders($extraHeaders = []): array
    {
        return array_replace_recursive($this->_baseHeaders, $extraHeaders);
    }
}
