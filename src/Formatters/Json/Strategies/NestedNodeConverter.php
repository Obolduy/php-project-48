<?php

namespace Hexlet\Code\Formatters\Json\Strategies;

use Hexlet\Code\Formatters\Json\DTOs\JsonDiffNode;
use Hexlet\Code\Nodes\DTOs\DiffNode;

readonly class NestedNodeConverter implements NodeConverterStrategyInterface
{
    public function __construct(
        private NodeConverterStrategyInterface $nodeConverter
    ) {
    }

    public function convert(DiffNode $node): JsonDiffNode
    {
        return new JsonDiffNode(
            key: $node->key,
            type: $node->type,
            children: array_map(fn (DiffNode $child) => $this->nodeConverter->convert($child), $node->children)
        );
    }
}
