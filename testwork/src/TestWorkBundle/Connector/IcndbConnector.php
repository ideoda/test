<?php

namespace App\TestWorkBundle\Connector;

use Symfony\Component\HttpClient\CurlHttpClient;

/**
 * Class IcndbConnector
 * @package App\TestWorkBundle\Connector
 */
class IcndbConnector
{
    /**
     * @var CurlHttpClient
     */
    protected $httpClient;

    /**
     * IcndbConnector constructor.
     * @param CurlHttpClient $httpClient
     */
    public function __construct(CurlHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     */
    public function getRandom(): array
    {
        $response = $this->httpClient
            ->request(
                'GET',
                'http://api.icndb.com/jokes/random?firstName=John&amp;lastName=Doe'
            )
            ->getContent()
        ;

        return json_decode($response, true);
    }
}