<?php

namespace App\Controller\Api;

use App\Service\ServerSideRender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SEOController extends Controller
{
    use JsonApi;

    /**
     * @Route("/seo", methods={"GET"})
     */
    public function index(Request $request)
    {
        $url = $request->get('url');
        $engine = $request->get('e', $this->getParameter('ssr_engine'));

        /** @var ServerSideRender $service */
        $service = $this->container->get("server_side_render");
        return $this->createJsonResponse($service->render($url, $engine));
    }
}
