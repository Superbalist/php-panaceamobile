<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Mockery;
use PHPUnit\Framework\TestCase;
use Superbalist\PanaceaMobile\PanaceaMobileAPI;

class PanaceaMobileAPITest extends TestCase
{
    public function testSetGetUri()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = new PanaceaMobileAPI($guzzleClient);
        $this->assertEquals('http://api.panaceamobile.com', $client->getUri());
        $client->setUri('http://127.0.0.1');
        $this->assertEquals('http://127.0.0.1', $client->getUri());
    }

    public function testSetGetUsername()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = new PanaceaMobileAPI($guzzleClient, 'my_username');
        $this->assertEquals('my_username', $client->getUsername());
        $client->setUsername('my_new_username');
        $this->assertEquals('my_new_username', $client->getUsername());
    }

    public function testSetGetPassword()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = new PanaceaMobileAPI($guzzleClient, 'my_username', 'my_password');
        $this->assertEquals('my_password', $client->getPassword());
        $client->setPassword('my_new_password');
        $this->assertEquals('my_new_password', $client->getPassword());
    }

    public function testSetGetUserAgent()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = new PanaceaMobileAPI($guzzleClient);
        $this->assertEquals('superbalist/php-panaceamobile', $client->getUserAgent());
        $client->setUserAgent('lorem-ipsum');
        $this->assertEquals('lorem-ipsum', $client->getUserAgent());
    }

    public function testMakeBaseUri()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = new PanaceaMobileAPI($guzzleClient);
        $client->setUri('http://api.panaceamobile.com//');
        $uri = $client->makeBaseUri('json');
        $this->assertEquals('http://api.panaceamobile.com/json', $uri);
    }

    public function testGetGlobalHeaders()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = new PanaceaMobileAPI($guzzleClient);
        $client->setUserAgent('My UserAgent String');
        $headers = $client->getGlobalHeaders();
        $this->assertArrayHasKey('User-Agent', $headers);
        $this->assertEquals('My UserAgent String', $headers['User-Agent']);
    }

    public function testGet()
    {
        $request = new Request(
            'GET',
            'http://api.panaceamobile.com/json?action=message_send',
            ['User-Agent' => 'superbalist/php-panaceamobile']
        );

        $guzzleClient = Mockery::mock(Client::class);
        $client = Mockery::mock(
            '\Superbalist\PanaceaMobile\PanaceaMobileAPI[createRequest,sendRequest]',
            [$guzzleClient]
        );
        $client->shouldAllowMockingProtectedMethods();
        $client->shouldReceive('createRequest')
            ->withArgs([
                'GET',
                'json?action=message_send',
            ])
            ->once()
            ->andReturn($request);
        $client->shouldreceive('sendRequest')
            ->with($request)
            ->once();

        $client->get('message_send');
    }

    public function testGetWithQueryString()
    {
        $request = new Request(
            'GET',
            'http://api.panaceamobile.com/json?action=message_send',
            ['User-Agent' => 'superbalist/php-panaceamobile']
        );

        $guzzleClient = Mockery::mock(Client::class);
        $client = Mockery::mock(
            '\Superbalist\PanaceaMobile\PanaceaMobileAPI[createRequest,sendRequest]',
            [$guzzleClient]
        );
        $client->shouldAllowMockingProtectedMethods();
        $client->shouldReceive('createRequest')
            ->withArgs([
                'GET',
                'json?hello=world&action=message_send',
            ])
            ->once()
            ->andReturn($request);
        $client->shouldreceive('sendRequest')
            ->with($request)
            ->once();

        $client->get('message_send', ['hello' => 'world']);
    }

    public function testSendMessage()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $client = Mockery::mock('\Superbalist\PanaceaMobile\PanaceaMobileAPI[get]', [$guzzleClient]);
        $client->shouldReceive('get')
            ->withArgs([
                'message_send',
                [
                    'to' => '+27000000000',
                    'text' => 'This is my message',
                    'from' => '+27111111111',
                    'report_mask' => 19,
                    'report_url' => null,
                    'charset' => null,
                    'data_coding' => null,
                    'message_class' => -1,
                    'auto_detect_encoding' => 0,
                ]
            ])
            ->once();
        $client->sendMessage('+27000000000', 'This is my message', '+27111111111');
    }
}