<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC;

/**
 * Interface SMSCMessageInterface.
 */
interface SMSCMessageInterface
{
    /**
     * Get the short message.
     *
     * @return string
     */
    public function content();

    /**
     * Get the to number.
     *
     * @return string
     */
    public function number();

    /**
     * Get the message properties as array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Convert the sms message to request sms parameters.
     *
     * @return array
     */
    public function toRequestParams();
}
