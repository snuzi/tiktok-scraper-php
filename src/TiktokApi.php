<?php

namespace sabri\tiktok;

use GuzzleHttp\Client;
use sabri\tiktok\exceptions\InvalidResponseException;
use sabri\tiktok\exceptions\LoginRequiredException;

class TiktokApi {

    /** @var string Base API URL of Tiktok */
    private $_baseUrl = 'https://api2.musical.ly';

    /** @var ApiParams */
    private $_apiParams;

    /**
     * @param $sessionQueryParameters device related extra query parameters
     */
    public function __construct(array $sessionQueryParameters)
    {
        $this->_apiParams = new ApiParams($sessionQueryParameters);
    }

    /**
     * Get user profile info
     *
     * @param string $uid Unique user id on Tiktok
     * @return array
     * @throws InvalidResponseException
     * @throws LoginRequiredException
     */
    public function getUser(string $uid): array
    {
        $extraParams = ['user_id' => $uid];

        $content = $this->request(
            'aweme/v1/user/',
            $extraParams
        );

        return $content;
    }

    /**
     * Makes request to Tiktok
     *
     * @param string $url relative path to the API endpoint
     * @param array $extraQueryParameters Extra query parameters to be appended.
     * @param array $extraHeaders Extra headers to be appended.
     *
     * @return array
     *
     * @throws LoginRequiredException if user should be logged in for the current request
     * @throws InvalidResponseException if there are errors in response
     * @throws InvalidResponseException if reponse body is empty
     */
    protected function request(
        string $url,
        array $extraQueryParameters = [],
        array $extraHeaders = []
    ): array
    {

        $client = new Client([
            'base_uri' => $this->_baseUrl,
            //'debug' => true
        ]);

        $response = $client->request('GET', $url, [
            'query' => $this->_apiParams->getQueryParams($extraQueryParameters),
            'headers' => $this->_apiParams->getHeaders($extraHeaders),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new InvalidResponseException('Problems fetching content');
        }

        $arrayContent = json_decode($response->getBody()->getContents(), true);

        // If empty response
        if (!$arrayContent) {
            throw new InvalidResponseException('Invalid response');
        }

        if (isset($arrayContent['status_code']) && $arrayContent['status_code'] > 0) {
            if ($arrayContent['status_code'] == 2483) {
                throw new LoginRequiredException($arrayContent['status_msg']);
            }
            throw new InvalidResponseException($arrayContent['status_msg'] ?? 'Invalid response');
        }

        return $arrayContent;
    }

    /**
     * Get user videos
     *
     * @param string $uid Unique user id on Tiktok
     * @return array
     * @throws InvalidResponseException
     * @throws LoginRequiredException
     */
    public function getUserVideos(string $uid): array
    {
        // TODO Implement pagination

        $extraParams = [
            'user_id' => $uid,
            'max_cursor' => 0,
            'type' => 0,
            'count' => 20,
        ];

        $content = $this->request(
            'aweme/v1/aweme/post/',
            $extraParams
        );

        $moreContent = [];

        // Pagination for user's videos
        while (isset($content['max_cursor']) && $content['has_more'] == 1) {
            $extraParams['max_cursor'] = $content['max_cursor'];

            $moreContent = $this->request(
                'aweme/v1/aweme/post/',
                $extraParams
            );

            if ($moreContent) {
                $content['has_more'] = $moreContent['has_more'];
                $content['max_cursor'] = $moreContent['max_cursor'];
                $content['min_cursor'] = $moreContent['min_cursor'];
                $content['aweme_list'] = array_merge($content['aweme_list'], $moreContent['aweme_list']);
            } else {
                break;
            }
        }

        return $content;
    }

    /**
     * Search users on Tiktok
     *
     * @param string $keyword a search term
     * @return array
     * @throws InvalidResponseException
     * @throws LoginRequiredException
     */
    public function searchUser(string $keyword): array
    {
        $extraParams = [
            'cursor' => 0,
            'count' => 10,
            'hot_search' => 0,
            'keyword' => $keyword,
            'type' => 1
        ];

        $content = $this->request(
            'aweme/v1/discover/search',
            $extraParams
        );

        return $content;
    }

    /**
     * Get a video details
     *
     * @param string $uid Unique video id on Tiktok
     * @return array
     * @throws InvalidResponseException
     * @throws LoginRequiredException
     */
    public function getPost(string $uid)
    {
        $extraParams = ['aweme_id' => $uid];

        $content = $this->request(
            'aweme/v1/aweme/detail',
            $extraParams
        );

        return $content;
    }

    /**
     * Search hashtags on Tiktok
     *
     * @param string $keyword
     * @return array list of to 10 found hashtags
     * @throws InvalidResponseException
     * @throws LoginRequiredException
     */
    public function searchHashtags(string $keyword)
    {
        $extraParams = [
            'cursor' => 0,
            'count' => 10,
            'hot_search' => 0,
            'keyword' => $keyword,
            'type' => 1
        ];

        $content = $this->request(
            'aweme/v1/challenge/search/',
            $extraParams
        );

        return $content;
    }

    /**
     * et hashtags madiea on Tiktok. Currently returns max top 50 videos.
     *
     * @param string $uid Tiktok hashtag unique ID
     * @param int $count limit results, currently only max top 50 videos will be returned
     * @return array list of videos
     * @throws InvalidResponseException
     * @throws LoginRequiredException
     */
    public function getHashtagMedia(string $uid, int $count = 50)
    {
        // TODO add support for pagination

        if ($count > 50) {
            $count = 50;
        }

        $extraParams = [
            'count' => $count,
            'offset' => 0,
            'max_cursor' => 0,
            'query_type' => 0,
            'is_cold_start' => 1,
            'pull_type' => 1,
            'ch_id' => $uid,
            'type' => 5
        ];

        $content = $this->request(
            'aweme/v1/challenge/aweme/',
            $extraParams
        );

        return $content;
    }
}
