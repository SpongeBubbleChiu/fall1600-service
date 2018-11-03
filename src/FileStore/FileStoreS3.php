<?php

namespace App\FileStore;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\File;

class FileStoreS3 implements FileStoreInterface
{
    protected $bucket;

    /** @var  S3Client */
    protected $client;
    protected $uidGenerator;
    protected $webUploadPrefix;

    public function __construct()
    {
        $this->uidGenerator = new Uid\RandomUidGenerator(static::class);
    }

    /**
     * @param string $uid
     * @param string $suffix
     * @param string $ext
     * @param string $pathName
     * @param null $downloadName
     * @return bool|void
     */
    public function write($uid, $suffix, $ext, $pathName, $downloadName = null)
    {
        $this->createFolder();
        $fileKey = ($suffix == null) ? "$uid.$ext" : "$uid.$suffix.$ext";
        $file = new File($pathName);
        $options = array(
            'Bucket' => $this->bucket,
            'Key' => "{$this->webUploadPrefix}{$fileKey}",
            'Body' => fopen($pathName, 'r+'),
            'ContentType' => $file->getMimeType(),
            'CacheControl' => 'max-age=604800',
            'ACL' => 'public-read',
        );

        if($downloadName !== null){
            unset($options['ContentType']);
            $options["ContentDisposition"] = sprintf("attachment; filename*=UTF-8''%s", urlencode($downloadName));
        }

        $this->client->putObject($options);
    }

    /**
     * @param string $uid
     * @param string $suffix
     * @param string $ext
     * @return bool|void
     */
    public function delete($uid, $suffix, $ext)
    {
        $this->client->deleteObject(array(
            'Bucket' => $this->bucket,
            'Key' => ($suffix == null) ? "$uid.$ext" : "$uid.$suffix.$ext",
        ));
    }

    /**
     * @return string
     */
    public function generateUid()
    {
        return $this->uidGenerator->generate();
    }

    public function webPath($uid)
    {
        return null;
    }

    protected function createFolder()
    {
        if ($this->webUploadPrefix == '') {
            return;
        }

        if ($this->client->doesObjectExist($this->bucket, $this->webUploadPrefix)) {
            return;
        }

        $this->client->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $this->webUploadPrefix,
            'Body' => '',
            'ACL' => 'public-read',
        ));
    }
}
