<?php

namespace App\Tests\FileStore;

use App\Tests\Fixture\BaseKernelTestCase;

class FileStoreLocalRandomTest extends BaseKernelTestCase
{
    public function test_inject()
    {
        //arrange
        $expected1 = $this->container->getParameter("file.store.path");
        $expected2 = $this->container->getParameter("file.store.path_level");

        //act
        $service = $this->container->get("file_store.local.random");

        //assert
        $this->assertEquals($expected1, $this->getObjectAttribute($service, 'storePath'));
        $this->assertEquals($expected2, $this->getObjectAttribute($service, 'pathLevel'));
    }
}
