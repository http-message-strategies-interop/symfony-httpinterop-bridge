<?php

namespace TheCodingMachine\HttpInteropBridge;

use Interop\Http\Message\Strategies\ServerActionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Zend\Diactoros\Response;

class SymfonyToHttpInteropBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        // HttpInterop middleware that appends 'bar' to the body
        $middlewareInterface = new class implements ServerActionInterface
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
