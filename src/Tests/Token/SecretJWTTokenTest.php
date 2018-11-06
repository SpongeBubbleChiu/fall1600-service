<?php

namespace App\Tests\Token;

use App\Tests\Fixture\BaseKernelTestCase;
use App\Token\SecretJWTToken;

class SecretJWTTokenTest extends BaseKernelTestCase
{
    public function test_inject()
    {
        //arrange
        $expected1 = SecretJWTToken::SECRET_PREFIX.$this->container->getParameter("secret");

        //act
        $service = $this->container->get("secret_jwt_token");

        //assert
        $this->assertEquals($expected1, $this->getObjectAttribute($service, 'secret'));
    }
}
