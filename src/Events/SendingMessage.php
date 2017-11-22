<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Events;

use NotificationChannels\SMSC\SMSCMessageInterface;

/**
 * Class SendingMessage.
 */
class SendingMessage
{
    /**
     * The SMSC message.
     *
     * @var SMSCMessageInterface
     */
    public $message;

    /**
     * SendingMessage constructor.
     *
     * @param $message
     */
    public function __construct(SMSCMessageInterface $message)
    {
        $this->message = $message;
    }
}
