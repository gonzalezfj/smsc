<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Clients;

use NotificationChannels\SMSC\SMSCMessageInterface;
use NotificationChannels\SMSC\Exceptions\CouldNotSendNotification;

/**
 * Interface SMSCClientInterface.
 */
interface SMSCClientInterface
{
    /**
     * Send the client request to the service.
     *
     * @throws CouldNotSendNotification If SMS Api returns false.
     * @return SMSCApiResponseInterface
     */
    public function sendRequest();

    /**
     * Add an sms message to request.
     *
     * @param  SMSCMessageInterface $smsMessage
     * @return void
     */
    public function addToRequest(SMSCMessageInterface $smsMessage);
}
