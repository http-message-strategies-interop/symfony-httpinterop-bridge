<?php

namespace TheCodingMachine\HttpInteropBridge;

use Interop\Http\Message\Strategies\ServerRequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zend\Diactoros\Response;

class SymfonyToHttpInteropBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        // HttpInterop middleware that appends 'bar' to the body
        $middlewareInterface = new class implements ServerRequestHandlerInterface
         {
             public function __invoke(ServerRequestInterface $request)
             {
                 $response = new Response();
                 $response->getBody()->write('foobar');

                 return $response;
             }
         };

        $bridge = new SymfonyToHttpInteropBridge($middlewareInterface);

        $request = SymfonyRequest::create('/', 'GET');
        $response = $bridge->handle($request);

        $this->assertEquals('foobar', $response->getContent());
    }
}
