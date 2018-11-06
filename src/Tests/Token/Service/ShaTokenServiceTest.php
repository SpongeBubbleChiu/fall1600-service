<?php

namespace App\Tests\Token\Service;

use App\Tests\Fixture\BaseKernelTestCase;

class ShaTokenServiceTest extends BaseKernelTestCase
{
    public function test_inject()
    {
        //arrange
        $expected1 = $this->container->getParameter("token_issuer");
        $expected2 = $this->container->getParameter("secret");

        //act
        $service = $this->container->get("token_service.sha");

        //assert
        $this->assertEquals($expected1, $this->getObjectAttribute($service, 'issuer'));
        $this->assertEquals($expected2, $this->getObjectAttribute($service, 'secret'));
    }
}
