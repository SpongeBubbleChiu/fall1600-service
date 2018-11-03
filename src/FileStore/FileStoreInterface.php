<?php
namespace App\FileStore;

interface FileStoreInterface
{
    /**
     * @param string $uid
     * @param string $suffix
     * @param string $ext
     * @param string $pathName
     * @return bool
     */
    public function write($uid, $suffix, $ext, $pathName, $downloadName = null);

    /**
     * @param string $uid
     * @param string $suffix
     * @param string $ext
     * @return bool
     */
    public function delete($uid, $suffix, $ext);

    /**
     * @return string
     */
    public function generateUid();

    public function webPath($uid);
}
