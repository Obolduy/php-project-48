<?php

namespace Hexlet\Code\Formatters\Json\Strategies;

use Hexlet\Code\Formatters\Json\DTOs\JsonDiffNode;
use Hexlet\Code\Formatters\Json\ValueNormalizer;
use Hexlet\Code\Nodes\DTOs\DiffNode;

readonly class ChangedNodeConverter implements NodeConverterStrategyInterface
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
            oldValue: $this->valueNormalizer->normalize($node->oldValue),
            newValue: $this->valueNormalizer->normalize($node->newValue)
        );
    }
}
