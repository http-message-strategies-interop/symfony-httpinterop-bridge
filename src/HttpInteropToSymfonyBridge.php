<?php

namespace TheCodingMachine\HttpInteropBridge;

use Interop\Http\Message\Strategies\ServerActionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * A HTTP Message Strategy that handles request via a Symfony HttpKernel.
 */
class HttpInteropToSymfonyBridge implements ServerActionInterface
{
    /**
     * @var HttpKernelInterface
     */
    private $symfonyHttpKernel;
    /**
     * @var HttpMessageFactoryInterface
     */
    private $httpMessageFactory;
    /**
     * @var HttpFoundationFactoryInterface
     */
    private $httpFoundationFactory;

    /**
     * @param HttpKernelInterface            $symfonyHttpKernel     The Symfony HttpKernel which handles the request.
     * @param HttpFoundationFactoryInterface $httpFoundationFactory The class in charge of translating PSR-7 request/response objects to Symfony objects. Defaults to Symfony default implementation
     * @param HttpMessageFactoryInterface    $httpMessageFactory    The class in charge of translating Symfony request/response objects to PSR-7 objects. Defaults to Symfony default implementation (that uses Diactoros)
     */
    public function __construct(HttpKernelInterface $symfonyHttpKernel, HttpFoundationFactoryInterface $httpFoundationFactory = null, HttpMessageFactoryInterface $httpMessageFactory = null)
    {
        $this->symfonyHttpKernel = $symfonyHttpKernel;
        $this->httpFoundationFactory = $httpFoundationFactory ?: new HttpFoundationFactory();
        $this->httpMessageFactory = $httpMessageFactory ?: new DiactorosFactory();
    }

    /**
     * Process a server request and return the produced response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $symfonyRequest = $this->httpFoundationFactory->createRequest($request);

        $symfonyResponse = $this->symfonyHttpKernel->handle($symfonyRequest);

        return $this->httpMessageFactory->createResponse($symfonyResponse);
    }
}
