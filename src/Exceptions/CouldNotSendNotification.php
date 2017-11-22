<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Exceptions;

/**
 * Class CouldNotSendNotification.
 */
class CouldNotSendNotification extends \Exception
{
    /**
     * Get a new could not send notification exception with
     * length error message.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded()
    {
        $message = 'The content length is too long for an sms message.';

        return new static($message);
    }

    /**
     * Get a new could not send notification exception with
     * missing recipient message.
     *
     * @return static
     */
    public static function missingRecipient()
    {
        $message = 'The recipient of the sms message is missing.';

        return new static($message);
    }

    /**
     * Get a new could not send notification exception with
     * missing Recipient message.
     *
     * @param  string $message
     * @return static
     */
    public static function apiFailed($message)
    {
        return new static($message);
    }
}
