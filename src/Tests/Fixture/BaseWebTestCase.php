<?php

namespace App\Tests\Fixture;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Routing\Generator\UrlGenerator;

class BaseWebTestCase extends WebTestCase
{
    use InstanceMutator;

    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    protected $client;

    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router */
    protected $router;

    /** @var  \Symfony\Component\HttpFoundation\Session\Session */
    protected $session;

    /** @var  \Faker\Generator */
    protected $faker;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $this->session = $this->client->getContainer()->get('session');
        $this->faker = $this->client->getContainer()->get('faker.generator');
    }

    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    protected function tearDown()
    {
        $this->client = null;
        parent::tearDown();
    }

    protected function writeServerSession()
    {
        $this->session->save();
        $cookie = new Cookie($this->session->getName(), $this->session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
