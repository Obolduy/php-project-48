<?php

namespace Hexlet\Code;

class Printer
{
    public static function print(array $content, int $fileNumber): void
    {
        echo "File #$fileNumber content:" . PHP_EOL;

        print_r($content);

        echo PHP_EOL;
    }
}

