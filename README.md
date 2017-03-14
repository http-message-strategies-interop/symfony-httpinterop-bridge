# HMS fork of thecodingmachine/symfony-httpinterop-bridge

Bridges between [Symfony HttpKernel](http://symfony.com/doc/current/components/http_kernel/introduction.html) and [HTTP Message Strategies (PSR pre-Draft)](https://github.com/http-message-strategies-interop/fig-standards/tree/http-message-strategies/proposed/http-message-strategies)


Those adapters are built on top of the existing [symfony/psr-http-message-bridge](https://github.com/symfony/psr-http-message-bridge) that bridges Symfony and PSR-7 HTTP messages.

> This bridge is currently based on [Common interfaces for HTTP Message Strategies](https://github.com/http-message-strategies-interop/http-message-strategies). As this is in an early stage, this might be subject to change!

## Usage

By default, the Symfony HttpFoundation and HttpKernel are used.
For PSR-7, the [Zend-Diactoros](https://github.com/zendframework/zend-diactoros) implementation is used.
These implementations can be changed if needed.

### Wrapping a HttpKernel

```php
// Use the HttpInteropToSymfonyBridge adapter
$requestHandlerStrategy = new HttpInteropToSymfonyBridge($yourHttpKernel);

// Handling PSR-7 requests
$psr7Response = $requestHandlerStrategy($psr7Request);
```

### Wrapping a PSR-7 callback


```php
// Use the SymfonyToHttpInteropBridge adapter
$symfonyKernel = new SymfonyToHttpInteropBridge($yourServerRequestHandler);

// Handling Symfony requests
$symfonyResponse = $symfonyKernel->handle($symfonyRequest);
```

## Related

* [HTTP Message Strategies PSR (pre-Draft)](https://github.com/http-message-strategies-interop/fig-standards/tree/http-message-strategies/proposed/http-message-strategies)
