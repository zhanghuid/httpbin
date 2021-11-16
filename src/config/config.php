<?php

declare(strict_types=1);

use Hyperf\Framework\Bootstrap\FinishCallback;
use Hyperf\Framework\Bootstrap\TaskCallback;
use Hyperf\View\Mode;
use Hyperf\ViewEngine\HyperfViewEngine;
use Hyperf\Watcher\Driver\ScanFileDriver;
use Hyperf\Server\Event;

return [
    'server' => [
        'settings' => [
            'pid_file' => BASE_PATH . '/httpbin.pid',
            // Task Worker 数量，根据您的服务器配置而配置适当的数量
            'task_worker_num' => 3,
            // // // 因为 `Task` 主要处理无法协程化的方法，所以这里推荐设为 `false`，避免协程下出现数据混淆的情况
            'task_enable_coroutine' => false,

        ],
        'callbacks' => [
            // Task callbacks
            Event::ON_TASK => [TaskCallback::class, 'onTask'],
            Event::ON_FINISH => [FinishCallback::class, 'onFinish'],
        ],
    ],

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
    'view' => [
        'engine' => HyperfViewEngine::class,
        // 不填写则默认为 Task 模式，推荐使用 Task 模式
        'mode' => Mode::TASK,
        'config' => [
            'view_path' => BASE_PATH . '/src/static',
            'cache_path' => BASE_PATH . '/src/cache',
        ]
    ]
];
