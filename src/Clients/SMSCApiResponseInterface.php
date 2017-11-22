<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Clients;

/**
 * Interface SMSCApiResponseInterface.
 */
interface SMSCApiResponseInterface
{
    /**
     * Get the error code of the SMSC Api.
     *
     * @return int
     */
    public function errorCode();

    /**
     * Get the error message of the SMSC Api.
     *
     * @return null|string
     */
    public function errorMessage();

    /**
     * Determine if the api responded with a success or not.
     *
     * @return bool
     */
    public function isSuccess();
}
