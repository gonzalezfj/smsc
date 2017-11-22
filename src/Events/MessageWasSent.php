<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Events;

use NotificationChannels\SMSC\SMSCMessageInterface;
use NotificationChannels\SMSC\Clients\SMSCApiResponseInterface;

/**
 * Class MessageWasSent.
 */
class MessageWasSent
{
    /**
     * The sms message.
     *
     * @var SMSCMessageInterface
     */
    public $message;

    /**
     * The Api response.
     *
     * @var SMSCApiResponseInterface
     */
    public $response;

    /**
     * MessageWasSent constructor.
     *
     * @param SMSCMessageInterface     $message
     * @param SMSCApiResponseInterface $response
     */
    public function __construct(SMSCMessageInterface $message, SMSCApiResponseInterface $response)
    {
        $this->message = $message;
        $this->response = $response;
    }
}
