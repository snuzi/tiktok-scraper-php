<?php
namespace tests;
use PHPUnit\Framework\TestCase;
use sabri\tiktok\ApiParams;
use sabri\tiktok\TiktokApi;
use Exception;
use sabri\tiktok\exceptions\InvalidResponseException;

class TiktokApiTest extends TestCase
{
    
    private $_tiktokClient;

    protected function setUp(): void
    {
        $this->_tiktokClient = $this->getTiktokClient();
    }

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

    public function _testSearchUser()
    {
        $keyword = 'real';

        $userData = $this->_tiktokClient->searchUser($keyword);

        $this->assertIsArray($userData['user_list']);
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
