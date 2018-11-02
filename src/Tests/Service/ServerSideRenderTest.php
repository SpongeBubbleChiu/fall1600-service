<?php

namespace App\Tests\Service;

use App\Tests\Fixture\BaseKernelTestCase;

class ServerSideRenderTest extends BaseKernelTestCase
{
    public function test_inject()
    {
        //arrange
        $expected1 = array(
          "http://localhost:8080",
          "http://127.0.0.1:8080",
        );
        $expected2 = "http://localhost:9999/render";
        $service = $this->container->get("server_side_render");

        //act

        //assert
        $this->assertEquals($expected1, $this->getObjectAttribute($service, 'allowOrigins'));
        $this->assertEquals($expected2, $this->getObjectAttribute($service, 'puppeteerApi'));
    }
}
