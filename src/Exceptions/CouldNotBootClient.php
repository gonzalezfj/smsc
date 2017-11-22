<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Exceptions;

/**
 * Class CouldNotBootClient.
 */
class CouldNotBootClient extends \Exception
{
    /**
     * Get a new could not boot client exception.
     *
     * @return static
     */
    public static function missingCredentials()
    {
        $message = 'The SMSC channel will not boot without API credentials.';

        return new static($message);
    }
}
