<?php

namespace Hexlet\Code\Parsers;

use Exception;

interface ParserInterface
{
    public function parse(string $content): array;
}
