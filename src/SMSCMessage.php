<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC;

/**
 * Class SMSCMessage.
 */
final class SMSCMessage implements SMSCMessageInterface
{
    /**
     * The message content.
     *
     * @var string
     */
    private $content;

    /**
     * The number to be notified.
     *
     * @var string
     */
    private $number;

    /**
     * SMSCMessage constructor.
     *
     * @param string             $content
     * @param string             $number
     */
    public function __construct($content, $number)
    {
        $this->content = $content;
        $this->number = $number;
    }

    /**
     * Get the message properties as array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'num' => $this->number(),
            'msj' => $this->content(),
        ]);
    }

    /**
     * Convert the sms message to sms parameters.
     *
     * @return array
     */
    public function toRequestParams()
    {
        return $this->toArray();
    }

    /**
     * Get the short message.
     *
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Get the to number.
     *
     * @return string
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * Property getter.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name();
    }
}
