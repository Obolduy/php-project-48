<?php

namespace Hexlet\Code\Formatters\Json\Strategies;

use Hexlet\Code\Formatters\Json\DTOs\JsonDiffNode;
use Hexlet\Code\Formatters\Json\ValueNormalizer;
use Hexlet\Code\Nodes\DTOs\DiffNode;

readonly class UnchangedNodeConverter implements NodeConverterStrategyInterface
{
    public function __construct(
        private ValueNormalizer $valueNormalizer
    ) {
    }

    public function convert(DiffNode $node): JsonDiffNode
    {
        return new JsonDiffNode(
            key: $node->key,
            type: $node->type,
            value: $this->valueNormalizer->normalize($node->value)
        );
    }
}
