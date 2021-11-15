<?php

require("vendor/autoload.php");

$exclude = ['./src'];
$pattern = '*.php';

$openapi = \OpenApi\Generator::scan(\OpenApi\Util::finder(__DIR__ . '/index.php', $exclude, $pattern));

// 生成yaml, 可以用来对yapi的同步
file_put_contents(__DIR__ . '/src/static/openapi.yaml', $openapi->toYaml());
