<?php

namespace Differ\Differ;

use Exception;
use Hexlet\Code\Differ;
use Hexlet\Code\Parser;
use Hexlet\Code\DiffBuilder;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $differ = new Differ(new Parser(), new DiffBuilder());

    try {
        $result = $differ->generate($pathToFile1, $pathToFile2);
    } catch (Exception $e) {
        $result = $e->getMessage();
    }

    return $result;
}
