# SMSC Notification Channel For Laravel 5.3
https://packagist.org/packages/gonzalezfj/smsc
[![Latest Version on Packagist](https://img.shields.io/packagist/v/gonzalezfj/smsc.svg?style=flat-square)](https://packagist.org/packages/gonzalezfj/smsc)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/gonzalezfj/smsc/master.svg?style=flat-square)](https://travis-ci.org/gonzalezfj/smsc)
[![StyleCI](https://styleci.io/repos/74304440/shield?branch=master)](https://styleci.io/repos/74304440)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/ce5f111f-1be4-4848-a87d-7b2570d153d4.svg?style=flat-square)](https://insight.sensiolabs.com/projects/ce5f111f-1be4-4848-a87d-7b2570d153d4)
[![Quality Score](https://img.shields.io/scrutinizer/g/gonzalezfj/smsc.svg?style=flat-square)](https://scrutinizer-ci.com/g/gonzalezfj/smsc)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/gonzalezfj/smsc/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/gonzalezfj/smsc/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/gonzalezfj/smsc.svg?style=flat-square)](https://packagist.org/packages/gonzalezfj/smsc)

This package makes it easy to send notifications using [SMSC](https://www.smsc.com.ar/) with Laravel 5.3.

## Contents

- [Installation](#installation)
    - [Setting up the SMSC service](#setting-up-the-SMSC-service)
- [Usage](#usage)
    - [Available methods](#available-methods)
    - [Available events](#available-events)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:

``` bash
composer require laravel-notification-channels/smsc
```

Next add the service provider to your `config/app.php`:

```php
...
'providers' => [
    ...
    NotificationChannels\SMSC\SMSCServiceProvider::class,
],
...
```

### Setting up the SMSC service

You will need to register to SMSC to use this channel.

The configuration given by the SMSC should be included within your `config/services.php` file:
                                                                     
```php
...
'SMSC' => [
    'http'       => [
        'endpoint' => 'https://www.smsc.com.ar/api/0.3/',
    ],
    'alias'   => '',
    'apikey'   => '',
    'timeout'    => 60,
],
...
```

## Usage

Follow Laravel's [documentation](https://laravel.com/docs/master/notifications) to add the channel to your Notification class.

```php
use NotificationChannels\SMSC\SMSCChannel;
use NotificationChannels\SMSC\SMSCMessage;

class ResetPasswordWasRequested extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SMSCChannel::class];
    }
    
    /**
     * Get the SMSC representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return string|\NotificationChannels\SMSC\SMSCMessage
     */
    public function toSMSC($notifiable) {
        return "Test notification";
        // Or
        return new SMSCMessage("Test notification", $notifiable->mobile_number);
    }
}
```

Don't forget to place the dedicated method for SMSC inside your notifiables. (e.g. User)

```php
class User extends Authenticatable
{
    use Notifiable;
    
    public function routeNotificationForSMSC()
    {
        return "115371885";
    }
}
```

### Available methods

Check out the constructor signature of SMSCMessage:

```php
public function __construct($content, $number);
```

### Available events

SMSC Notification channel comes with two handy events which provides the required information about the SMS messages.

1. **Message Was Sent** (`NotificationChannels\SMSC\Events\MessageWasSent`)

This event is fired shortly after the message is sent. An example handler is presented below:

```php
namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\SMSC\Events\MessageWasSent;

class SentMessageHandler
{
    /**
     * Handle the event.
     *
     * @param  MessageWasSent  $event
     * @return void
     */
    public function handle(MessageWasSent $event)
    {
        $response = $event->response;
        $message = $event->message;

        // The message properties.
        \Log::info($message->content());
        \Log::info($message->number());

        // Message as array.
        \Log::info($message->toArray());

        // API Response properties.
        \Log::info($response->isSuccess());
        \Log::info($response->errorCode());
        \Log::info($response->errorMessage());
    }
}
```

2. **Sending Message** (`NotificationChannels\SMSC\Events`)

This event is fired just before the send request. An example handler is presented below.

```php
namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\SMSC\Events\SendingMessage;

class SendingMessageHandler
{
    /**
     * Handle the event.
     *
     * @param  SendingMessage  $event
     * @return void
     */
    public function handle(SendingMessage $event)
    {
        $message = $event->message;

        // The message properties.
        \Log::info($message->content());
        \Log::info($message->number());

        // Message as array.
        \Log::info($message->toArray());
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email facujgg@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Facundo J Gonzalez](https://github.com/gonzalezfj)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
