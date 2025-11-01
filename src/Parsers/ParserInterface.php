<?php

namespace Hexlet\Code\Parsers;

use Hexlet\Code\Parsers\Exceptions\AbstractParserException;

interface ParserInterface
{
    /**
     * @param string $content
     * @return array
     * @throws AbstractParserException
     */
    public function parse(string $content): array;
}
