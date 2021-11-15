<?php

declare(strict_types=1);

namespace Huid\HttpBin\Support;

use Hyperf\HttpMessage\Server\Response;
use Hyperf\Nano\Factory\AppFactory;
use Hyperf\Watcher\Driver\ScanFileDriver;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Huid\HttpBin\Support;

$app = AppFactory::createBase();

$app->config([
    'server.settings.pid_file' => BASE_PATH . '/httpbin.pid',
    'watcher' => [
        'driver' => ScanFileDriver::class,
        'bin' => 'php',
        'command' => 'index.php start',
        'watch' => [
            'dir' => ['src/*'],
            'file' => ['index.php'],
            'scan_interval' => 2000,
        ],
    ],
]);

// record exception
$app->addExceptionHandler(function ($throwable, Response $response) {
    Support\record($response->getBody()->getContents());
    Support\record($throwable->getMessage());
});


// cors
$app->addMiddleware(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
    $headers = [
        'Access-Control-Allow-Origin' => $request->getHeaderLine('Origin') ?: '*',
        'Access-Control-Allow-Credentials' => 'true',
        // 'Access-Control-Allow-Headers' => 'DNT,Keep-Alive,User-Agent,Cache-Control,Content-Type,Authorization',
    ];

    // option request, should return it
    if ($request->getMethod() === 'OPTIONS') {
        $headers['Access-Control-Allow-Methods'] = 'GET, POST, PUT, DELETE, PATCH, OPTIONS';
        $headers['Access-Control-Max-Age'] = 3600;

        return Support\response($headers);
    }

    if ($newHeaders = $request->getHeaderLine('Access-Control-Request-Headers')) {
        $headers['Access-Control-Request-Headers'] = $newHeaders;
    }

    Support\response($headers);
    return $handler->handle($request);
});

return $app;
