<?php

namespace TheCodingMachine\HttpInteropBridge;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class HttpInteropToSymfonyBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        // Symfony middleware that returns 'foo'
        $symfonyMiddleware = new class implements HttpKernelInterface
         {
             public function handle(SymfonyRequest $request, $type = self::MASTER_REQUEST, $catch = true)
             {
                 return new SymfonyResponse('foo');
             }
         };

        $bridge = new HttpInteropToSymfonyBridge($symfonyMiddleware);

        $request = new ServerRequest([], [], new Uri('/'), 'GET');
        $response = $bridge($request);

        $this->assertEquals('foo', (string) $response->getBody());
    }
}
