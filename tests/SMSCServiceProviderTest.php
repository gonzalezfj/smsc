<?php
/**
 * Author: Facundo J Gonzalez
 * Date: 17/11/2017.
 */

namespace NotificationChannels\SMSC\Test;

use Mockery as M;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Container\ContextualBindingBuilder;
use NotificationChannels\SMSC\SMSCServiceProvider;

class SMSCServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    private $app;
    private $contextualBindingBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->app = M::mock(Application::class);
        $this->contextualBindingBuilder = M::mock(ContextualBindingBuilder::class);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_should_provide_services_on_boot()
    {
        $this->app->shouldReceive('when')
                  ->once()
                  ->andReturn($this->contextualBindingBuilder);
        $this->contextualBindingBuilder->shouldReceive('needs')
                                       ->once()
                                       ->andReturn($this->contextualBindingBuilder);
        $this->contextualBindingBuilder->shouldReceive('give')
                                       ->once();

        $provider = new SMSCServiceProvider($this->app);

        $provider->boot();
    }
}
