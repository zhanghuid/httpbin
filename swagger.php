<?php

use function Symfony\Component\String\b;

require("vendor/autoload.php");

$exclude = ['./src'];
$pattern = '*.php';

$openapi = \OpenApi\Generator::scan(\OpenApi\Util::finder(__DIR__ . '/index.php', $exclude, $pattern));

/**
 * 命令行参数list
 */
$help = function () {
    $string =  <<<HELP
    \033[36m Usage\033[0m:
        php swagger.php --url=127.0.0.1
    \033[36m Options\033[0m:
        \033[33m url \033[0m: access to openapi.yaml url
 _____   _____   _____   __   _       ___   _____   _  
/  _  \ |  _  \ | ____| |  \ | |     /   | |  _  \ | | 
| | | | | |_| | | |__   |   \| |    / /| | | |_| | | | 
| | | | |  ___/ |  __|  | |\   |   / / | | |  ___/ | | 
| |_| | | |     | |___  | | \  |  / /  | | | |     | | 
\_____/ |_|     |_____| |_|  \_| /_/   |_| |_|     |_| 
        
An Swagger html generate for PHP
Version: 0.0.1
    \n
HELP;

    die($string);
};

function formatGetOption($args)
{
    return function ($arg, $default = '') use ($args) {
        $options = [];
        foreach ($args as $key => $value) {
            if (strpos($value, '--') !== false) {
                $input = explode('=', $value);
                if ($input < 1) {
                    die("not valid input option");
                }

                $options[$input[0]] = $input[1];
            }
        }

        return $options["--{$arg}"] ?? $default;
    };
}

// 生成yaml, 可以用来对yapi的同步
$source = $openapi->toYaml();

/**
 * 获取参数
 */
$argvCount = count($argv);
$getOption = formatGetOption($argv);
switch ($argvCount) {
    case 2:
        $url = $getOption('url', '127.0.0.1');
        $replaceString = 'httpbin.mykeep.fun';
        $source = str_replace($replaceString, $url, $source);
        break;
    default:
        $help();
}

file_put_contents(__DIR__ . '/src/static/openapi.yaml', $source);
