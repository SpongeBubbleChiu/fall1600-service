<?php

namespace App\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SEOController extends Controller
{
    use JsonApi;

    public function index(Request $request)
    {
        $url = $request->get('url');
        $engine = $request->get('e', $this->getParameter('ssr_engine'));
        return $this->createJsonResponse($this->serverSideRender->render($url, $engine));
    }
}
