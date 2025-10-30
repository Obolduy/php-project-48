<?php

namespace Hexlet\Code\Formatters;

use Hexlet\Code\Formatters\Json\NodeConverter;
use Hexlet\Code\Formatters\Json\ValueNormalizer;

readonly class JsonFormatter
{
    private NodeConverter $nodeConverter;

    public function __construct()
    {
        $this->nodeConverter = new NodeConverter(new ValueNormalizer());
    }

    public function format(array $tree): string
    {
        return json_encode($this->nodeConverter->convertTree($tree), JSON_PRETTY_PRINT);
    }
}
