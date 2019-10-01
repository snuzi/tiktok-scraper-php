<?php

require __DIR__ . '/../vendor/autoload.php';
(Dotenv\Dotenv::create(__DIR__ . '/../'))->load();

use sabri\tiktok\TiktokApi;

$client = new TiktokApi([
    'device_id' => getenv('DEVICE_ID'),
    'iid' => getenv('IID'),
    'openudid' => getenv('OPENUDID')
]);

$uid = '6693776501107033094';
$user = $client->getUser($uid);

var_dump($user);
