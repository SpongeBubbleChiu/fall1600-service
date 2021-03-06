<?php
namespace App\Token\Service;

use App\Token\Service\TokenRequest\TokenRequestInterface;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

class ShaTokenService extends AbstractTokenService
{
    protected $secret;
    protected $issuer;

    public function injectIssuer($tokenIssuer)
    {
        $this->issuer = $tokenIssuer;
    }

    public function injectSecret($secret)
    {
        $this->secret = $secret;
    }

    public function sign(TokenRequestInterface $tokenRequest)
    {
        $signer = new Sha256();
        return (string)$this->applyData($this->issuer, $tokenRequest)
            ->sign($signer, $this->secret)
            ->getToken();
    }

    /**
     * @param $jwtToken
     * @return Token|null
     */
    public function parse($jwtToken)
    {
        $result = $this->verify($jwtToken);
        if($result === false){
            return null;
        }
        return new Token($result);
    }

    protected function verify($jwtToken)
    {
        try {
            $time = $this->getCurrentTime();
            $token = (new Parser())->parse($jwtToken);

            $validationData = new ValidationData();
            $validationData->setIssuer($this->issuer);
            $validationData->setCurrentTime($time);

            if (!$token->verify(new Sha256(), $this->secret)) {
                return false;
            }

            if (!$token->validate($validationData)) {
                return false;
            }

            return json_decode(json_encode($token->getClaims()), true);
        }
        catch (\Exception $e){
            return false;
        }
    }
}
