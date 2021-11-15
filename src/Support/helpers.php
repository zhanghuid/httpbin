<?php

declare(strict_types=1);

namespace Huid\HttpBin\Support;

use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * get Logger instance
 *
 * @return StdoutLoggerInterface
 */
function logger()
{
    return make(StdoutLoggerInterface::class);
}

/**
 * get Request instance
 *
 * @return \Hyperf\HttpServer\Request|mixed
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
function request()
{
    return make(RequestInterface::class);
}

/**
 * get Server Request instance
 *
 * @return \Hyperf\HttpMessage\Server\Request
 */
function serverRequest()
{
    return make(ServerRequestInterface::class);
}

/**
 * get or set Response header
 *
 * @param array $headers
 * @return ResponseInterface
 */
function response($headers = [])
{
    /** @var ResponseInterface  $response */
    $response = Context::get(ResponseInterface::class);

    if (!$headers) {
        return $response;
    }

    foreach ($headers as $key => $value) {
        $response = $response->withHeader($key, $value);
    }

    Context::set(ResponseInterface::class, $response);
    return $response;
}

/**
 * get all header from client
 *
 * @return array
 */
function get_headers()
{
    $headers = [];
    $request = ServerRequest();
    foreach ($request->getHeaders() as $name => $values) {
        $field = implode('-', array_map('ucfirst', explode('-', $name)));
        $headers[$field] = implode(", ", $values);
    }
    unset($name, $values);
    return $headers;
}

/**
 * get client ip
 *
 * @return string
 */
function get_ip()
{
    $request = serverRequest();
    $ipAddress = $request->getHeaderLine('x-forwarded-for');
    if (verify_ip($ipAddress)) {
        return $ipAddress;
    }
    $ipAddress = $request->getHeaderLine('remote-host');
    if (verify_ip($ipAddress)) {
        return $ipAddress;
    }
    $ipAddress = $request->getHeaderLine('x-real-ip');
    if (verify_ip($ipAddress)) {
        return $ipAddress;
    }
    $ipAddress = $request->getServerParams()['remote_addr'] ?? '0.0.0.0';
    if (verify_ip($ipAddress)) {
        return $ipAddress;
    }
    return '0.0.0.0';
}

/**
 * verify ip
 *
 * @param string $ip
 * @return bool
 */
function verify_ip(string $ip)
{
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

/**
 * debug tool
 *
 * @param mixed $msg
 * @return void
 */
function record($msg)
{
    file_put_contents('./debug.log', date('Y-m-d H:i:s') . ': ' . var_export($msg, true) . PHP_EOL, FILE_APPEND);
}

/**
 * get all input files
 *
 * @return array
 */
function get_files()
{
    $inputFiles = request()->getUploadedFiles();

    $files = [];
    array_walk(
        $inputFiles,
        function (UploadedFile $file, $key) use (&$files) {
            $files[$key] = 'data:image/jpeg;base64,' . base64_encode($file->getStream()->getContents());
        }
    );

    return $files;
}

/**
 * Returns request dict of given keys.
 *
 * @param ...$keys
 * @return array
 */
function get_dict(...$keys)
{
    $serverRequest = request();
    // $_keys = ['url', 'args', 'form', 'data', 'origin', 'headers', 'files', 'json', 'method'];
    $_data = $serverRequest->getBody()->getContents();

    $_json = json_decode($_data);
    if (json_last_error()) {
        $_data = null;
    }

    $_values = [
        'url' => $serverRequest->fullUrl(),
        'args' => $serverRequest->getQueryParams(),
        'form' => $serverRequest->getParsedBody(),
        'data' => $_data,
        'origin' => get_ip(),
        'headers' => get_headers(),
        'files' => get_files(),
        'json' => $_json,
        'method' => $serverRequest->getMethod(),
    ];

    $out = [];
    foreach ($keys as $key) {
        $out[$key] = $_values[$key];
    }

    return $out;
}
