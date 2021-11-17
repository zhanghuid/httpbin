<?php

declare(strict_types=1);

use Huid\HttpBin\Support;
use OpenApi\Annotations as OA;
use Hyperf\View\RenderInterface;

/**
 * @OA\Info(
 *     version="0.0.1",
 *     title="httpbin api"
 * )
 */

/**
 * @OA\Server(
 *      url="{schema}://127.0.0.1:9501",
 *      description="OpenApi parameters",
 *      @OA\ServerVariable(
 *          serverVariable="schema",
 *          enum={"https", "http"},
 *          default="http"
 *      )
 * )
 */

/**
 *  @OA\Tag(
 *   name="HTTP Methods",
 *   description="Testing different HTTP verbs"
 * )
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/
require_once __DIR__ . '/vendor/autoload.php';



/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| the core file mean the application begin.
| with watcher which can run debug mode.
| with exception log record middleware.
| with cors middleware.
|
|
*/
$app = require __DIR__.'/src/Support/core.php';

$app->get('/', function (RenderInterface $render) {
    return $render->render('index');
});

/**
 * An Get API endpoint.
 *
 * @OA\Get(
 *   path="/get",
 *   description="The request's query parameters.",
 *   @OA\Response(response=200, description="The request's query parameters."),
 *   tags={"HTTP Methods"}
 * )
 */
$app->get('/get', function () {
    return Support\get_dict('args', 'headers', 'origin', 'url');
});


/**
 * @OA\Put(
 *     path="/put",
 *     description="The request's PUT parameters.",
 *     @OA\Response(
 *         response=200,
 *         description="The request's PUT parameters."
 *     ),
 *     tags={"HTTP Methods"}
 * )
 */
$app->put('/put', function () {
    return Support\get_dict("url", "args", "form", "data", "origin", "headers", "files", "json");
});


/**
 * @OA\Post (
 *     path="/post",
 *     summary="The request's POST parameters.",
 *     @OA\Response(
 *         response=200,
 *         description="The request's POST parameters."
 *     ),
 *     tags={"HTTP Methods"}
 * )
 */
$app->post('/post', function () {
    return Support\get_dict("url", "args", "form", "data", "origin", "headers", "files", "json");
});


/**
 * @OA\Delete   (
 *     path="/delete",
 *     description="The request's DELETE parameters.",
 *     @OA\Response(
 *         response=200,
 *         description="The request's DELETE parameters."
 *     ),
 *     tags={"HTTP Methods"}
 * )
 */
$app->delete('/delete', function () {
    return Support\get_dict("url", "args", "form", "data", "origin", "headers", "files", "json");
});

/**
 * @OA\Patch  (
 *     path="/patch",
 *     description="The request's PATCH parameters.",
 *     @OA\Response(
 *         response=200,
 *         description="The request's PATCH parameters."
 *     ),
 *     tags={"HTTP Methods"}
 * )
 */
$app->patch('/patch', function () {
    return Support\get_dict("url", "args", "form", "data", "origin", "headers", "files", "json");
});


$app->run();
