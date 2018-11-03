<?php

namespace App\Tests\FileStore;

use App\FileStore\FileStoreLocalTimer;
use App\FileStore\Uid\UidGeneratorInterface;
use App\Tests\Fixture\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class FileStoreLocalTimerTest extends BaseTestCase
{
    public function test__construct()
    {
        //arrange
        $pathLevel = 3;
        $storePath = '/foo/bar/baz';
        $filesystem = new Filesystem();

        //act
        $fileStore = new FileStoreLocalTimer($storePath, $pathLevel, $filesystem);

        //assert
        $this->assertInstanceOf(UidGeneratorInterface::class, $this->getObjectAttribute($fileStore, 'uidGenerator'));
        $this->assertEquals($storePath, $this->getObjectAttribute($fileStore, 'storePath'));
        $this->assertEquals($filesystem, $this->getObjectAttribute($fileStore, 'filesystem'));
    }

    public function test_makeDir()
    {
        //arrange
        $timer = strtotime('2016/02/03')*1000;
        $rand = rand(1000, 9999);
        $storePath = 'test_store_path';
        $uid = "$timer-$rand";
        $fileStore = $this->getMockBuilder(FileStoreLocalTimer::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();
        $this->setObjectAttribute($fileStore, 'storePath', $storePath);

        //act
        $result = $this->callObjectMethod($fileStore, 'makeDir', $uid);

        //assert
        $this->assertEquals("$storePath/2016/02/03", $result);
    }
}