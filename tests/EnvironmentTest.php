<?php


class EnvironmentTest extends PHPUnit_Framework_TestCase
{

    public function testEnvironment()
    {

        $httpClient = new \GuzzleHttp\Client();

        $response = $httpClient->post('http://localhost:8000/api/post', [
            'json' => [
                'hello' => 'world',
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

}