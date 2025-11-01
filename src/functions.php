<?php

namespace Differ\Differ;

use Exception;
use Hexlet\Code\Differ\DiffBuilder;
use Hexlet\Code\Differ\Differ;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Parsers\Common\Parser;

function genDiff(
    string $pathToFile1,
    string $pathToFile2,
    string|OutputFormatEnum $format = 'stylish'
): string {
    if (is_string($format)) {
        $format = OutputFormatEnum::tryFrom($format) ?? OutputFormatEnum::STYLISH;
    }

    $differ = new Differ(new Parser(), new DiffBuilder());

    try {
        $result = $differ->generate($pathToFile1, $pathToFile2, $format);
    } catch (Exception $e) {
        $result = $e->getMessage();
    }

    return $result;
}
