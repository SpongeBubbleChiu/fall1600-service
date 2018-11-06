<?php
namespace App\Token\Service;

use App\Token\Service\TokenRequest\TokenRequestInterface;

interface TokenServiceInterface
{
    /**
     * @param TokenRequestInterface $tokenRequest
     * @return string
     */
    public function sign(TokenRequestInterface $tokenRequest);

    /**
     * @param $token
     * @return Token|null
     */
    public function parse($jwtToken);
}
