<?php

namespace Hexlet\Code;

class Cli
{
    public static function run(): void
    {
        $config = require_once __DIR__ . '/../config/app.php';

        $app = new Application($config);

        $exitCode = $app->run();

        exit($exitCode);
    }
}
