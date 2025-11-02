<?php

namespace Hexlet\Code\Formatters\Json;

use Hexlet\Code\Formatters\Json\DTOs\JsonDiffNode;
use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;

readonly class NodeConverter
{
    public function __construct(
        private ValueNormalizer $valueNormalizer
    ) {
    }

    public function convert(DiffNode $node): JsonDiffNode
    {
        return match ($node->type) {
            DiffNodeTypeEnum::ADDED, DiffNodeTypeEnum::REMOVED, DiffNodeTypeEnum::UNCHANGED => new JsonDiffNode(
                key: $node->key,
                type: $node->type,
                value: $this->valueNormalizer->normalize($node->value)
            ),
            DiffNodeTypeEnum::CHANGED => new JsonDiffNode(
                key: $node->key,
                type: $node->type,
                oldValue: $this->valueNormalizer->normalize($node->oldValue),
                newValue: $this->valueNormalizer->normalize($node->newValue)
            ),
            DiffNodeTypeEnum::NESTED => new JsonDiffNode(
                key: $node->key,
                type: $node->type,
                children: array_map(fn (DiffNode $child) => $this->convert($child), $node->children)
            ),
        };
    }

    /**
     * @param array<DiffNode> $nodes
     * @return array<JsonDiffNode>
     */
    public function convertTree(array $nodes): array
    {
        return array_map(fn (DiffNode $node) => $this->convert($node), $nodes);
    }
}
