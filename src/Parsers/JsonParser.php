<?php

namespace Hexlet\Code\Parsers;

use Hexlet\Code\Parsers\Exceptions\JsonParserException;

class JsonParser implements ParserInterface
{
    /**
     * @throws JsonParserException
     */
    public function parse(string $content): array
    {
        $data = json_decode($content);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonParserException("Invalid JSON: " . json_last_error_msg());
        }

        return get_object_vars($data);
    }
}
