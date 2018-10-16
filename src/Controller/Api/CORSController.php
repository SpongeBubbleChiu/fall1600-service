<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CORSController extends Controller
{
    /**
     * @Route("/{any}", methods={"OPTIONS"}, requirements={"any" = ".*"})
     */
    public function optionsAction()
    {
        return new Response();
    }
}