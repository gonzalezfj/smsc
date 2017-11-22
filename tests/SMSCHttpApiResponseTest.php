<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Test;

use NotificationChannels\SMSC\Clients\Http\SMSCHttpApiResponse;

class SMSCHttpApiResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function error_message_return_null_when_the_status_code_is_zero()
    {
        $httpApiResponse = new SMSCHttpApiResponse('{
													    "code": 200,
													    "message": "Mensaje enviado.",
													    "data": {
													        "id": 11769334,
													        "sms": 1
													    }
													}');

        $this->assertNull($httpApiResponse->errorMessage());
        $this->assertNull($httpApiResponse->errorCode());
        $this->assertTrue($httpApiResponse->isSuccess());
    }
}
