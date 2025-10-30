<?php

namespace Differ\Differ;

use Exception;
use Hexlet\Code\Differ\DiffBuilder;
use Hexlet\Code\Differ\Differ;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Parsers\Parser;

function genDiff(
    string $pathToFile1,
    string $pathToFile2,
    OutputFormatEnum $format = OutputFormatEnum::STYLISH
): string {
    $differ = new Differ(new Parser(), new DiffBuilder());

    try {
        $result = $differ->generate($pathToFile1, $pathToFile2, $format);
    } catch (Exception $e) {
        $result = $e->getMessage();
    }

    return $result;
}
