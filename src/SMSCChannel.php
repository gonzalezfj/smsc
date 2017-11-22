<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\SMSC\Events\MessageWasSent;
use NotificationChannels\SMSC\Events\SendingMessage;
use NotificationChannels\SMSC\Clients\SMSCClientInterface;
use NotificationChannels\SMSC\Clients\SMSCApiResponseInterface;
use NotificationChannels\SMSC\Exceptions\CouldNotSendNotification;

/**
 * Class SMSCChannel.
 */
final class SMSCChannel
{
    /**
     * The guzzle http client.
     *
     * @var SMSCClientInterface
     */
    private $client;

    /**
     * The Laravel event dispatcher implementation.
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * SMSCChannel constructor.
     *
     * @param SMSCClientInterface   $client
     * @param Dispatcher            $dispatcher
     */
    public function __construct(SMSCClientInterface $client, Dispatcher $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @throws \NotificationChannels\SMSC\Exceptions\CouldNotSendNotification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('SMSC');

        if (empty($to)) {
            throw CouldNotSendNotification::missingRecipient();
        }

        $message = $notification->toSMSC($notifiable);

        if (is_string($message)) {
            $message = new SMSCMessage($message, $to);
        }

        if (strlen($message->content()) > 160) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }

        $this->client->addToRequest($message);
        $this->fireSendingEvent($message);
        $response = $this->sendMessage();
        $this->fireSentEvent($message, $response);
    }

    /**
     * Send the message.
     *
     * @return SMSCApiResponseInterface
     */
    private function sendMessage()
    {
        $response = $this->client->sendRequest();

        return $response;
    }

    /**
     * Fire the sending event for the prepared message.
     *
     * @param SMSCMessage $message
     */
    private function fireSendingEvent(SMSCMessage $message)
    {
        $this->dispatcher->fire(new SendingMessage($message));
    }

    /**
     * Fire  the sent event for the message.
     *
     * @param SMSCMessage         $message
     * @param SMSCApiResponseInterface $response
     */
    private function fireSentEvent(SMSCMessage $message, SMSCApiResponseInterface $response)
    {
        $this->dispatcher->fire(new MessageWasSent($message, $response));
    }
}
