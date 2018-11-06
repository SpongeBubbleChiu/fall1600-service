<?php

namespace App\Token\Service;

use App\Token\Service\TokenRequest\TokenRequestInterface;
use Lcobucci\JWT\Builder;

abstract class AbstractTokenService implements TokenServiceInterface
{
    protected function getCurrentTime()
    {
        return time();
    }

    /**
     * @param $issuer
     * @param TokenRequestInterface $tokenRequest
     * @return Builder
     */
    protected function applyData($issuer, TokenRequestInterface $tokenRequest)
    {
        $time = $this->getCurrentTime();
        $builder = new Builder();
        return $builder
            ->setIssuer($issuer)
            ->setExpiration($time + $tokenRequest->getTtl())
            ->setIssuedAt($time)
            ->set('id', $tokenRequest->getId())
            ->set('type', $tokenRequest->getType())
            ->set('data', $tokenRequest->getData());
    }
}
