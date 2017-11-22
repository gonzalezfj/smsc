<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Clients\Http;

use NotificationChannels\SMSC\Clients\SMSCApiResponseInterface;
use NotificationChannels\SMSC\Exceptions\CouldNotSendNotification;

/**
 * Class SMSCHttpApiResponse.
 */
final class SMSCHttpApiResponse implements SMSCApiResponseInterface
{
    private $responseAttributes;

    /**
     * Create a message response.
     *
     * @param  string $responseBody
     */
    public function __construct($responseBody)
    {
        $this->responseAttributes = $this->readResponseBodyString($responseBody);
    }

    /**
     * Get the error code of the SMSC Api.
     *
     * @return int
     */
    public function errorCode()
    {
        if ($this->responseAttributes['code'] == 200) {
            return;
        }

        return $this->responseAttributes['code'];
    }

    /**
     * Get the error message og the SMSC Api.
     *
     * @return null|string
     */
    public function errorMessage()
    {
        if ($this->responseAttributes['code'] == 200) {
            return;
        }

        return $this->responseAttributes['message'];
    }
    
    /**
     * Determine if the api responded with a success or not.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->responseAttributes['code'] == 200;
    }

    /**
     * Read the message response body string.
     *
     * @param $responseBodyString
     * @return array
     */
    private function readResponseBodyString($responseBodyString)
    {
        $decoded = json_decode($responseBodyString, true);

        
        if ($decoded == null) {
            throw CouldNotSendNotification::apiFailed('Invalid JSON');
        }

        return $decoded;
    }
}
