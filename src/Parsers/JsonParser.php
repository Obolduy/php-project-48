<?php

namespace Hexlet\Code\Parsers;

use Exception;

class JsonParser implements ParserInterface
{
    /**
     * @throws Exception
     */
    public function parse(string $content): array
    {
        $data = json_decode($content, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON: " . json_last_error_msg());
        }

        return $data;
    }
}
