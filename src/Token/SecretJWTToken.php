<?php
namespace App\Token;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Symfony\Component\HttpFoundation\Request;

class SecretJWTToken
{
    const SECRET_PREFIX = 'app_secret.';
    protected $secret;

    public function injectSecret($secret)
    {
        $this->secret = self::SECRET_PREFIX.$secret;
    }

    protected function getCurrentTime()
    {
        return time();
    }

    public function createJWTToken(Request $request, $data)
    {
        $issuer = $request->getHttpHost();
        $audience = $request->headers->get('Origin', $issuer);
        return $this->sign($issuer, $audience, $data);
    }

    public function sign($issuer, $audience, $data)
    {
        $time = $this->getCurrentTime();
        $signer = new Sha256();
        $builder = new Builder();
        $builder
            ->setIssuedAt($time)
            ->setNotBefore($time)
            ->setIssuer($issuer)
            ->setAudience($audience)
            ->setExpiration($time+3600)
        ;
        return (string) $builder
            ->set('data', $data)
            ->sign($signer, $this->secret)
            ->getToken();
    }

    public function verify($jwtToken, $issuer, $audience = null)
    {
        try {
            $time = $this->getCurrentTime();
            $token = (new Parser())->parse($jwtToken);
            if ($token->verify(new Sha256(), $this->secret)) {
                $validationData = new ValidationData();
                $validationData->setIssuer($issuer);
                if($audience) {
                    $validationData->setAudience($audience);
                }
                $validationData->setCurrentTime($time);
                if (!$token->validate($validationData)) {
                    return null;
                }
                return json_decode(json_encode($token->getClaim('data')), true);
            }
        }
        catch (\Exception $e){
            return null;
        }
    }
}
