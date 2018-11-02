<?php

namespace App\Tests\Fixture;

use App\Kernel;
use Symfony\Component\DependencyInjection\Container;

class BaseKernelTestCase extends BaseTestCase
{
    /** @var Kernel */
    protected $kernel;

    /** @var Container */
    protected $container;

    protected function setUp()
    {
        parent::setUp();
        $this->bootKernel();
    }

    protected function tearDown()
    {
        $this->kernel->shutdown();
        unset($this->kernel);
        unset($this->container);
        parent::tearDown();
    }

    protected function bootKernel()
    {
        $this->kernel = new Kernel('test', true);
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();
    }
}
