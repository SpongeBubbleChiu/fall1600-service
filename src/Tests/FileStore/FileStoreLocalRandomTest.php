<?php

namespace App\Tests\FileStore;

use App\FileStore\FileStoreLocalRandom;
use App\Tests\Fixture\BaseKernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

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

    public function test_write_dir_not_exists()
    {
        //arrange
        $ext = 'jpg';
        $suffix = 'origin';
        $uid = 'test_uid';
        $dir = 'test_target_dir';
        $src = 'test_source_file';
        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(array('exists', 'mkdir', 'copy'))
            ->disableOriginalConstructor()
            ->getMock();
        $filesystem
            ->expects($this->once())
            ->method('exists')
            ->willReturnCallback(function($path) use($dir){
                $this->assertEquals($dir, $path);
                return false;
            });
        $filesystem
            ->expects($this->once())
            ->method('mkdir')
            ->willReturnCallback(function($path) use($dir){
                $this->assertEquals($dir, $path);
            });
        $filesystem
            ->expects($this->once())
            ->method('copy')
            ->willReturnCallback(function($srcForTest, $targetForTest) use($dir, $uid, $suffix, $ext, $src){
                $this->assertEquals($src, $srcForTest);
                $this->assertEquals("$dir/$uid.$suffix.$ext", $targetForTest);
            });
        $fileStore = $this->getMockBuilder(FileStoreLocalRandom::class)
            ->setMethods(array('makeDir'))
            ->disableOriginalConstructor()
            ->getMock();
        $fileStore
            ->expects($this->once())
            ->method('makeDir')
            ->willReturnCallback(function($uidForTest) use($uid, $dir){
                $this->assertEquals($uid, $uidForTest);
                return $dir;
            });
        $this->setObjectAttribute($fileStore, 'filesystem', $filesystem);

        //act
        $fileStore->write($uid, $suffix, $ext, $src);

        //assert
    }

    public function test_write_dir_exists()
    {
        //arrange
        $ext = 'jpg';
        $suffix = 'origin';
        $uid = 'test_uid';
        $dir = 'test_target_dir';
        $src = 'test_source_file';
        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(array('exists', 'mkdir', 'copy'))
            ->disableOriginalConstructor()
            ->getMock();
        $filesystem
            ->expects($this->once())
            ->method('exists')
            ->willReturnCallback(function($path) use($dir){
                $this->assertEquals($dir, $path);
                return true;
            });
        $filesystem
            ->expects($this->never())
            ->method('mkdir')
            ->willReturnCallback(function($path) use($dir){
                $this->assertEquals($dir, $path);
            });
        $filesystem
            ->expects($this->once())
            ->method('copy')
            ->willReturnCallback(function($srcForTest, $targetForTest) use($dir, $uid, $suffix, $ext, $src){
                $this->assertEquals($src, $srcForTest);
                $this->assertEquals("$dir/$uid.$suffix.$ext", $targetForTest);
            });
        $fileStore = $this->getMockBuilder(FileStoreLocalRandom::class)
            ->setMethods(array('makeDir'))
            ->disableOriginalConstructor()
            ->getMock();
        $fileStore
            ->expects($this->once())
            ->method('makeDir')
            ->willReturnCallback(function($uidForTest) use($uid, $dir){
                $this->assertEquals($uid, $uidForTest);
                return $dir;
            });
        $this->setObjectAttribute($fileStore, 'filesystem', $filesystem);

        //act
        $fileStore->write($uid, $suffix, $ext, $src);

        //assert
    }

    public function test_generateUid()
    {
        //arrange
        $fileStore = $this->getMockBuilder(FileStoreLocalRandom::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();
        $generator = $this->getMockBuilder(RandomUidGenerator::class)
            ->setMethods(array('generate'))
            ->disableOriginalConstructor()
            ->getMock();
        $generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('abcdefg');
        $this->setObjectAttribute($fileStore, 'uidGenerator', $generator);

        //act
        $result = $fileStore->generateUid();

        //arrange
        $this->assertEquals('abcdefg', $result);

    }

    public function test_delete_file_not_exists()
    {
        //arrange
        $ext = 'jpg';
        $suffix = 'origin';
        $uid = 'test_uid';
        $dir = 'test_target_dir';
        $fileStore = $this->getMockBuilder(FileStoreLocalRandom::class)
            ->setMethods(array('makeDir'))
            ->disableOriginalConstructor()
            ->getMock();
        $fileStore
            ->expects($this->once())
            ->method('makeDir')
            ->willReturnCallback(function($uidForTest) use($uid, $dir){
                $this->assertEquals($uid, $uidForTest);
                return $dir;
            });
        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(array('exists', 'remove'))
            ->disableOriginalConstructor()
            ->getMock();
        $filesystem
            ->expects($this->once())
            ->method('exists')
            ->willReturnCallback(function($path) use($uid, $ext, $suffix, $dir){
                $this->assertEquals("$dir/$uid.$suffix.$ext", $path);
                return false;
            });
        $filesystem
            ->expects($this->never())
            ->method('remove')
            ->willReturn(null);
        $this->setObjectAttribute($fileStore, 'filesystem', $filesystem);

        //act
        $result = $fileStore->delete($uid, $suffix, $ext);

        //assert
        $this->assertFalse($result);
    }

    public function test_makeDir()
    {
        //arrange
        $storePath = 'test_store_path';
        $pathLevel = 5;
        $uid = '0123456789';
        $fileStore = $this->getMockBuilder(FileStoreLocalRandom::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();
        $this->setObjectAttribute($fileStore, 'pathLevel', $pathLevel);
        $this->setObjectAttribute($fileStore, 'storePath', $storePath);

        //act
        $result = $this->callObjectMethod($fileStore, 'makeDir', $uid);

        //assert
        $this->assertEquals("$storePath/0/1/2/3/4", $result);
    }
}
