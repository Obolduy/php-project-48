<?php

namespace Hexlet\Code\Formatters;

class OutputFormatter
{
    public static function help(string $doc): void
    {
        echo $doc . PHP_EOL;
    }

    public static function version(string $name, string $version): void
    {
        echo "$name $version" . PHP_EOL;
    }

    public static function error(string $message): void
    {
        echo "Error: $message" . PHP_EOL;
    }
}
