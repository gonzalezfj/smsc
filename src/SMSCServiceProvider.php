<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\SMSC\Clients\Http\SMSCClient;
use NotificationChannels\SMSC\Clients\SMSCClientInterface;

/**
 * Class SMSCServiceProvider.
 */
class SMSCServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(SMSCChannel::class)
                  ->needs(SMSCClientInterface::class)
                  ->give(function () {
                      $config = config('services.SMSC', [
                          'http'       => [
                              'endpoint' => 'https://www.smsc.com.ar/api/0.3/',
                          ],
                          'alias'   => '',
                          'apikey'   => '',
                          'timeout'    => '',
                      ]);

                      $endpoint = $config['http']['endpoint'];
                      $alias = $config['alias'];
                      $apikey = $config['apikey'];
                      $timeout = $config['timeout'];

                      $guzzleHttpClient = new Client(['timeout' => $timeout]);
                      $SMSCClient = new SMSCClient($guzzleHttpClient, $endpoint, $alias, $apikey);

                      return $SMSCClient;
                  });
    }
}
