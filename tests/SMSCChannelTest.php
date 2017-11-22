<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016
 * Time: 22:17.
 */

namespace NotificationChannels\SMSC\Test;

use Mockery as M;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\SMSC\SMSCChannel;
use NotificationChannels\SMSC\SMSCMessage;
use NotificationChannels\SMSC\SMSCMessageInterface;
use NotificationChannels\SMSC\Clients\SMSCClientInterface;
use NotificationChannels\SMSC\Clients\SMSCApiResponseInterface;
use NotificationChannels\SMSC\Exceptions\CouldNotSendNotification;

class SMSCChannelTest extends \PHPUnit_Framework_TestCase
{
    private $message;
    private $client;
    private $channel;
    private $apiResponse;
    private $eventDispatcher;

    public function setUp()
    {
        parent::setUp();

        $this->client = M::mock(SMSCClientInterface::class);
        $this->eventDispatcher = M::mock(Dispatcher::class);
        $this->channel = new SMSCChannel($this->client, $this->eventDispatcher);
        $this->message = M::mock(SMSCMessageInterface::class);
        $this->apiResponse = M::mock(SMSCApiResponseInterface::class);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_notification()
    {
        $this->client->shouldReceive('addToRequest')
                     ->once();
        $this->client->shouldReceive('sendRequest')
                     ->once()
                     ->andReturn($this->apiResponse);
        $this->eventDispatcher->shouldReceive('fire')
                              ->twice();

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_text_notification()
    {
        $this->client->shouldReceive('addToRequest')
                     ->once();
        $this->client->shouldReceive('sendRequest')
                     ->once()
                     ->andReturn($this->apiResponse);
        $this->eventDispatcher->shouldReceive('fire')
                              ->twice();

        $this->channel->send(new TestNotifiable(), new TestNotificationWithString());
    }

    /** @test */
    public function it_does_not_send_sms_when_recipient_is_missing()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->channel->send(new TestNotifiableWithNoRecipients(), new TestNotification());
    }

    /** @test */
    public function it_does_not_send_sms_when_content_is_too_long()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->channel->send(new TestNotifiable(), new TestNotificationWithTooLongContent());
    }
}

class TestNotifiable
{
    public function routeNotificationFor()
    {
        return '113578459';
    }
}

class TestNotifiableWithNoRecipients
{
    public function routeNotificationFor()
    {
    }
}

class TestNotification extends Notification
{
    public function toSMSC()
    {
        return new SMSCMessage('hello', '113578459');
    }
}

class TestNotificationWithString extends Notification
{
    public function toSMSC()
    {
        return 'hello';
    }
}

class TestNotificationWithTooLongContent extends Notification
{
    public function toSMSC()
    {
        return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel vestibulum massa. Nulla in condimentum justo. Pellentesque tempus ultrices fringilla. Pellentesque leo metuss.';
    }
}
