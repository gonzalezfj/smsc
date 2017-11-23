<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Test;

use Mockery as M;
use GuzzleHttp\Client;
use Psr\Http\Message\MessageInterface;
use NotificationChannels\SMSC\SMSCMessage;
use NotificationChannels\SMSC\Clients\Http\SMSCClient;
use NotificationChannels\SMSC\Exceptions\CouldNotBootClient;
use NotificationChannels\SMSC\Exceptions\CouldNotSendNotification;

class SMSCClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $httpClient;
    private $httpMessage;

    public function setUp()
    {
        parent::setUp();

        $this->httpClient = M::mock(Client::class);
        $this->httpMessage = M::mock(MessageInterface::class);
        $this->client = new SMSCClient($this->httpClient, 'foo', 'bar', 'baz');
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_should_add_a_message_to_request_and_send_it()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')
                          ->once()
                          ->andReturn('{
                                          "code": 200,
                                          "message": "Mensaje enviado.",
                                          "data": {
                                            "id": 11769334,
                                            "sms": 1
                                          }
                                       }');
        $this->httpMessage->shouldReceive('getStatusCode')
                          ->once()
                          ->andReturn(200);
        $this->client->addToRequest(new SMSCMessage('content', 'number'));
        $apiResponse = $this->client->sendRequest();
        $this->assertTrue($apiResponse->isSuccess());
    }

    /** @test */
    public function it_should_not_boot_without_an_endpoint()
    {
        $this->setExpectedException(CouldNotBootClient::class);

        $this->clientWithNoEndpoint = new SMSCClient($this->httpClient, '', 'bar', 'baz');
    }

    /** @test */
    public function it_should_not_boot_without_an_alias()
    {
        $this->setExpectedException(CouldNotBootClient::class);

        new SMSCClient($this->httpClient, 'foo', '', 'baz');
    }

    /** @test */
    public function it_should_not_boot_without_an_apikey()
    {
        $this->setExpectedException(CouldNotBootClient::class);

        new SMSCClient($this->httpClient, 'foo', 'bar', '');
    }

    /** @test */
    public function it_should_handle_api_fail_with_known_errors()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')
                          ->once()
                          ->andReturn('{
                                          "code": 401,
                                          "message": "Acceso no autorizado.",
                                          "data": []
                                       }');
        $this->httpMessage->shouldReceive('getStatusCode')
                          ->once()
                          ->andReturn(200);

        $this->setExpectedException(CouldNotSendNotification::class);

        $this->client->addToRequest(new SMSCMessage('content', 'number'));
        $response = $this->client->sendRequest();
        $this->assertEquals(401, $response->errorCode());
        $this->assertEquals('Acceso no autorizado.', $response->errorMessage());
    }

    /** @test */
    public function it_should_handle_api_fail_with_unknown_errors()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')->once()->andReturn('error foo-bar');

        $this->httpMessage->shouldReceive('getStatusCode')
                          ->once()
                          ->andReturn(200);

        $this->setExpectedException(CouldNotSendNotification::class);

        $this->client->addToRequest(new SMSCMessage('content', 'number'));
        $response = $this->client->sendRequest();
        $this->assertNull($response->errorCode());
    }

    /** @test */
    public function it_should_handle_api_fail_with_http_errors()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getStatusCode')
                          ->once()
                          ->andReturn(503);

        $this->httpMessage->shouldReceive('getReasonPhrase')
                          ->once()
                          ->andReturn('Service unavailable');

        $this->setExpectedException(CouldNotSendNotification::class);

        $this->client->addToRequest(new SMSCMessage('content', 'number'));
        $response = $this->client->sendRequest();
        $this->assertNull($response->errorCode());
    }
}
