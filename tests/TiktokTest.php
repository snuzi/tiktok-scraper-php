<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use sabri\tiktok\exceptions\InvalidResponseException;
use sabri\tiktok\TiktokApi;

class TiktokApiTest extends TestCase
{

    private $_tiktokClient;

    public function testGetUser()
    {
        $uid = '6693776501107033094';

        $userData = $this->_tiktokClient->getUser($uid);

        $this->assertEquals($userData['user']['uid'], $uid);
    }

    public function testGetUserVideos()
    {
        $uid = '6693776501107033094';

        $userData = $this->_tiktokClient->getUserVideos($uid);

        $this->assertIsArray($userData['aweme_list']);
        $this->assertIsArray($userData['aweme_list'][0]);
        $this->assertEquals($userData['aweme_list'][0]['author_user_id'], $uid);
    }

    public function testGetUserNotExist()
    {
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('Invalid parameters');

        $uid = 'notValidId';
        $userData = $this->_tiktokClient->getUser($uid);
    }

    public function testSearchUser()
    {
        $keyword = 'real';

        $userData = $this->_tiktokClient->searchUser($keyword);

        $this->assertIsArray($userData['user_list']);
    }

    public function testSearchHashtags()
    {
        $keyword = 'realmadrid';

        $responseData = $this->_tiktokClient->searchHashtags($keyword);

        $this->assertIsArray($responseData['challenge_list']);
        $this->assertIsArray($responseData['challenge_list'][0]);
        $this->assertEquals($responseData['challenge_list'][0]['challenge_info']['cha_name'], $keyword);
        $this->assertEquals($responseData['challenge_list'][0]['challenge_info']['cid'], '19484');
    }

    public function testGetHashtagVideos()
    {
        $uid = '19484';
        $responseData = $this->_tiktokClient->getHashtagMedia($uid);

        $this->assertIsArray($responseData['aweme_list']);

        //Result should contain top 50 videos
        $this->assertCount(50, $responseData['aweme_list']);

        $this->assertIsArray($responseData['aweme_list'][0]);
        $this->assertIsArray($responseData['aweme_list'][0]['video']);

        // Should return only 20 videos
        $responseData = $this->_tiktokClient->getHashtagMedia($uid, 20);
        $this->assertCount(20, $responseData['aweme_list']);
    }

    protected function setUp(): void
    {
        $this->_tiktokClient = $this->getTiktokClient();
    }

    private function getTiktokClient()
    {
        $client = new TiktokApi([
            'device_id' => getenv('DEVICE_ID'),
            'iid' => getenv('IID'),
            'openudid' => getenv('OPENUDID')
        ]);

        return $client;
    }
}
