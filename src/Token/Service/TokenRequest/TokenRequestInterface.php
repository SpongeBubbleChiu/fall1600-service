<?php

namespace App\Token\Service\TokenRequest;

interface TokenRequestInterface
{
    public function getId();
    public function getType();
    public function getData();
    public function getTtl();
}
