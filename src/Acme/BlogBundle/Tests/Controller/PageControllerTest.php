<?php

namespace Acme\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\BlogBundle\Tests\Fixtures\Entity\LoadPageData;

class PageControllerTest extends WebTestCase
{
    public function testJsonGetPageAction()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/api/v1/pages/1.json');

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
    }

    public function testJsonPostPageAction()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/pages.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"title1","body":"body1"}'
        );

        $this->assertJsonResponse($client->getResponse(), 201, false);
    }

    public function testJsonPostPageActionShouldReturn400WithBadParameters()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/pages.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"titles":"title1","bodys":"body1"}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, false);
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
}
