<?php

namespace Hexlet\Code\Formatters;

use Exception;
use Hexlet\Code\Formatters\Exceptions\JsonFormatterException;
use Hexlet\Code\Formatters\Json\NodeConverter;
use Hexlet\Code\Formatters\Json\ValueNormalizer;

readonly class JsonFormatter
{
    private NodeConverter $nodeConverter;

    public function __construct()
    {
        $this->nodeConverter = new NodeConverter(new ValueNormalizer());
    }

    /**
     * @throws JsonFormatterException
     */
    public function format(array $tree): string
    {
        $result = json_encode($this->nodeConverter->convertTree($tree), JSON_PRETTY_PRINT);

        if ($result === false) {
            throw new JsonFormatterException('Failed to encode JSON: ' . json_last_error_msg());
        }

        return $result;
    }
}
