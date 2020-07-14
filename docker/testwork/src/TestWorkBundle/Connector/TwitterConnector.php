<?php

namespace App\TestWorkBundle\Connector;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use TwitterAPIExchange;

/**
 * Class TwitterConnector
 * @package App\TestWorkBundle\Connector
 */
class TwitterConnector
{
    protected $settings = [
        'oauth_access_token'        => '1248698770545618946-5jpQyXGkk33m6ZmYkY20MKCOaFZNgr',
        'oauth_access_token_secret' => 'pMNHCelTWuZRZAgFB5PhUcLwv3btRSI1VA9lQp3qpCkRM',
        'consumer_key'              => '4QNnyw5cxrUrEixBDoAMTse0j',
        'consumer_secret'           => 'lq9Hrwgy4nSXr2iHZpD4aKSYUi4YRCQkAnPmID5ioD4NNUmFjl',
    ];

    /**
     * @param string $name
     * @param int    $limit
     * @return array
     * @throws \Exception
     */
    public function getLastTweets(string $name, int $limit): array
    {
        $twitterapi = new TwitterAPIExchange($this->settings);

        $url           = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $requestMethod = 'GET';
        $getfield      = '?screen_name='.$name.'&count='.$limit;

        return json_decode($twitterapi->setGetfield($getfield)
                                      ->buildOauth($url, $requestMethod)
                                      ->performRequest(), $assoc = true);
    }
}