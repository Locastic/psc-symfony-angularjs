<?php

namespace Acme\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    public function testHeadRoute()
    {
        $client = static::createClient();

        $client->request('HEAD',  sprintf('/api/v1/pages/%d.json', 1), array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200, false);
    }

    public function testJsonNewPageAction()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/v1/pages/new.json',
            array(),
            array()
        );

        $this->assertJsonResponse($client->getResponse(), 200, true);
        $this->assertEquals(
            '{"children":{"title":[],"body":[]}}',
            $client->getResponse()->getContent(),
            $client->getResponse()->getContent());
    }

//    public function testJsonPutPageActionShouldCreate()
//    {
//        $client = static::createClient();
//
//        $id = 0;
//        $client->request('GET', sprintf('/api/v1/pages/%d.json', $id), array('ACCEPT' => 'application/json'));
//
//        $this->assertEquals(404, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
//
//        $client->request(
//            'PUT',
//            sprintf('/api/v1/pages/%d.json', $id),
//            array(),
//            array(),
//            array('CONTENT_TYPE' => 'application/json'),
//            '{"title":"abc","body":"def"}'
//        );
//
//        $this->assertJsonResponse($client->getResponse(), 201, false);
//    }


//    public function testJsonPutPageActionShouldModify()
//    {
//        $client = static::createClient();
//
//        $client->request('GET', sprintf('/api/v1/pages/%d.json', 1), array('ACCEPT' => 'application/json'));
//        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
//
//        $client->request(
//            'PUT',
//            sprintf('/api/v1/pages/%d.json', 1),
//            array(),
//            array(),
//            array('CONTENT_TYPE' => 'application/json'),
//            '{"title":"abc","body":"def"}'
//        );
//
//        $this->assertJsonResponse($client->getResponse(), 204, false);
//        $this->assertTrue(
//            $client->getResponse()->headers->contains(
//                'Location',
//                sprintf('http://localhost/api/v1/pages/%d.json', 1)
//            ),
//            $client->getResponse()->headers
//        );
//    }


    public function testJsonPatchPageAction()
    {
        $client = static::createClient();

        $client->request(
            'PATCH',
            sprintf('/api/v1/pages/%d.json', 1),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"body":"def"}'
        );

        $this->assertJsonResponse($client->getResponse(), 204, false);
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/v1/pages/%d.json', 1)
            ),
            $client->getResponse()->headers
        );
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

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}
