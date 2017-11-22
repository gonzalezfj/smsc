<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Test;

use Carbon\Carbon;
use NotificationChannels\SMSC\SMSCMessage;
use NotificationChannels\SMSC\SMSCMessageInterface;

class SMSCMessageTest extends \PHPUnit_Framework_TestCase
{
    private $smsMessage;

    /** @test */
    public function it_should_be_constructed()
    {
        $this->smsMessage = new SMSCMessage('foo', '2615998325');
        $this->assertInstanceOf(SMSCMessageInterface::class, $this->smsMessage);
    }

    /** @test */
    public function it_should_return_properties()
    {
        $this->smsMessage = new SMSCMessage($content = 'foo', $number = '2615998325');
        $this->assertEquals($this->smsMessage->content, $content);
        $this->assertEquals($this->smsMessage->number, $number);
    }
}
