<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Clients\Http;

use GuzzleHttp\Client as GuzzleHttpClient;
use NotificationChannels\SMSC\SMSCMessageInterface;
use NotificationChannels\SMSC\Clients\SMSCClientInterface;
use NotificationChannels\SMSC\Clients\SMSCApiResponseInterface;
use NotificationChannels\SMSC\Exceptions\CouldNotBootClient;
use NotificationChannels\SMSC\Exceptions\CouldNotSendNotification;

/**
 * Class SMSCClient.
 */
final class SMSCClient implements SMSCClientInterface
{
    /**
     * The end point.
     *
     * @var string
     */
    private static $endPoint;

    /**
     * The guzzle http client.
     *
     * @var GuzzleHttpClient
     */
    protected $httpClient;

    /**
     * The request parameters.
     *
     * @var array
     */
    protected $requestParams = [];

    /**
     * Initialize the dependencies.
     *
     * @param GuzzleHttpClient $client
     * @param string           $endpoint
     * @param string           $alias
     * @param string           $apikey
     */
    public function __construct(GuzzleHttpClient $client, $endpoint, $alias, $apikey)
    {
        $this->httpClient = $client;
        $this->boot($endpoint, $alias, $apikey);
    }

    /**
     * Load the sms service specific configurations.
     *
     * @param  string $endpoint
     * @param  string $alias
     * @param  string $apikey
     * @return void
     * @throws CouldNotBootClient
     */
    protected function boot($endpoint, $alias, $apikey)
    {
        if (! $this->hasCredentials($endpoint, $alias, $apikey)) {
            throw CouldNotBootClient::missingCredentials();
        }

        self::$endPoint = $endpoint;
        $this->requestParams['alias'] = $alias;
        $this->requestParams['apikey'] = $apikey;
        $this->requestParams['cmd'] = 'enviar';
    }

    /**
     * Add an sms message to request.
     *
     * @param  SMSCMessageInterface $smsMessage
     * @return void
     */
    public function addToRequest(SMSCMessageInterface $smsMessage)
    {
        $smsParams = array_filter($smsMessage->toRequestParams());

        $this->requestParams = array_merge($this->requestParams, $smsParams);
    }

    /**
     * Send the client request to the service.
     *
     * @throws CouldNotSendNotification If SMS Api returns false.
     * @return SMSCApiResponseInterface
     */
    public function sendRequest()
    {

        $guzzleResponse = $this->httpClient->request('GET', self::$endPoint, [
            'query' => $this->requestParams,
        ]);

        if ($guzzleResponse->getStatusCode() != 200) {
            throw CouldNotSendNotification::apiFailed($guzzleResponse->getReasonPhrase());
        }

        $response = new SMSCHttpApiResponse((string) $guzzleResponse->getBody());

        if (! $response->isSuccess()) {
            $message = $response->errorMessage().'['.$response->errorCode().']';
            throw CouldNotSendNotification::apiFailed($message);
        }
        return $response;
    }

    /**
     * Validate the configuration provided api credentials.
     *
     * @param  string $endpoint
     * @param  string $alias
     * @param  string $apikey
     * @throws CouldNotBootClient
     * @return bool
     */
    private function hasCredentials($endpoint, $alias, $apikey)
    {
        return $endpoint != '' && $alias != '' && $apikey != '';
    }
}
