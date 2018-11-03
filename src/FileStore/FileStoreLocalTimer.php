<?php

namespace App\FileStore;

use Symfony\Component\Filesystem\Filesystem;

class FileStoreLocalTimer extends FileStoreLocalRandom
{
    public function __construct($storePath, $pathLevel, Filesystem $filesystem)
    {
        parent::__construct($storePath, $pathLevel, $filesystem);
        $this->uidGenerator = new Uid\TimerUidGenerator();
        $this->storePath = $storePath;
        $this->filesystem = $filesystem;
    }

    protected function makeDir($uid)
    {
        list($timer, $rand) = explode('-', $uid);
        $dir = strftime("{$this->storePath}/%Y/%m/%d", floor($timer/1000));
        return $dir;
    }
}
