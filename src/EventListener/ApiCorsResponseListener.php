<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ApiCorsResponseListener
{
    protected $allowOrigins = array();

    public function __construct()
    {
        $this->allowOrigins = array_filter(explode(",", getenv("ALLOW_ORIGINS")));
    }

    /**
     * @DI\Observe("kernel.request", priority=48)
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        var_dump("onKernelRequest");
        if(!$event->isMasterRequest()) {
            var_dump("aaa");
            return;
        }

        var_dump("bbb");
        $request = $event->getRequest();
        if (($origin = $request->headers->get('origin')) === null ||
            ($request->isMethod(Request::METHOD_POST) && $request->headers->get('Content-Type') == 'application/x-www-form-urlencoded')
        ) {
            return;
        }

        var_dump("ccc");

        if ($this->isSameOrigin($request)) {
            $this->setCors($request, $origin);
            return;
        }

        var_dump("ddd");

        if (!$this->isValidOrigin($origin)) {
            $event->setResponse(new Response('CORS Access Deny', Response::HTTP_FORBIDDEN));
            return;
        }

        var_dump("eee");
        $this->setCors($request, $origin);
        if ($request->isMethod('OPTIONS')) {
            var_dump("fff");
            $response = new Response();
            $this->setResponseCORS($response, $request->attributes->get('_cors'));
            $event->setResponse($response);
            return;
        }
    }

    protected function setCors(Request $request, $origin)
    {
        $request->attributes->set('_cors', array(
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => 'PUT, GET, POST, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, Accept, Authorization',
            'Access-Control-Expose-Headers' => 'Content-Disposition, Content-Type',
            'Access-Control-Max-Age' => 86400,  // 1 day
        ));
    }

    protected function isSameOrigin(Request $request)
    {
        return $request->getSchemeAndHttpHost() === $request->headers->get('origin');
    }

    /**
     * @DI\Observe("kernel.response")
     */
    public function onResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();
        if ($cors = $request->attributes->get('_cors')) {
            $this->setResponseCORS($response, $cors);
        }
    }

    protected function setResponseCORS(Response $response, $cors)
    {
        foreach ($cors as $key => $val) {
            $response->headers->set($key, $val);
        }
    }

    protected function isValidOrigin($origin)
    {
        if (!preg_match('/^https?:\/\/(.*)/i', $origin, $match)) {
            return false;
        }

        $host = strtolower($match[1]);
        return $this->allowOrigins && in_array($host, $this->allowOrigins);
    }

    /**
     * @DI\Observe("kernel.exception")
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $exception = $event->getException();
        if ($exception instanceof HttpException) {
            if (in_array('application/json', $event->getRequest()->getAcceptableContentTypes())) {
                $event->setResponse(new JsonResponse(array(
                    'msg' => sprintf('%d %s',
                        $exception->getStatusCode(),
                        Response::$statusTexts[$exception->getStatusCode()]
                    )
                ), $exception->getStatusCode()));
            }
        }
    }
}