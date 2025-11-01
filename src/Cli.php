<?php

namespace Hexlet\Code;

class Cli
{
    public static function run(array $config): void
    {
        $app = new Application($config);

        $exitCode = $app->run();

        exit($exitCode);
    }
}
