<?php

namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ServerSideRender
{
    const ENGINE_PUPPETEER = 'puppeteer';

    protected $allowOrigins;

    protected $puppeteerApi;

    public function render($url, $engine = self::ENGINE_PUPPETEER)
    {
        if (is_null($url)) {
            throw new BadRequestHttpException();
        }

        $parsed = parse_url($url);
        if(!$parsed || !isset($parsed['host']) || !isset($parsed['scheme']) || !preg_match('/^https?$/i', $parsed['scheme'])) {
            throw new NotAcceptableHttpException();
        }

        $hostPort = $parsed['host'];
        if (isset($parsed['port'])) {
            $hostPort.=":{$parsed['port']}";
        }
        if (!$this->allowOrigins || !in_array($hostPort, $this->allowOrigins)) {
            throw new AccessDeniedHttpException();
        }

        return $this->renderWithoutCheck($url, $engine);
    }

    protected function renderWithoutCheck($url, $engine)
    {
        $result = $this->renderPuppeteer($url);
        return $result;
    }

    protected function renderPuppeteer($url)
    {
        $client = new Client();
        $response = $client->request('GET', $this->puppeteerApi, array(
            'query' => array(
                'url' => $url,
            ),
        ));

        if($response->getStatusCode() != Response::HTTP_OK){
            return array(
                'status' => false,
                'content' => $response->getBody(),
                'reason' => 'error',
                'statusCode' => $response->getStatusCode()
            );
        }
        return json_decode($response->getBody(), true);
    }
}
