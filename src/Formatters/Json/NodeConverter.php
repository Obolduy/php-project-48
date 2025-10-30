<?php

namespace Hexlet\Code\Formatters\Json;

use Hexlet\Code\Formatters\Json\DTOs\JsonDiffNode;
use Hexlet\Code\Formatters\Json\Strategies\NodeConverterStrategyInterface;
use Hexlet\Code\Nodes\DTOs\DiffNode;

readonly class NodeConverter implements NodeConverterStrategyInterface
{
    private NodeConverterFactory $factory;

    public function __construct(ValueNormalizer $valueNormalizer)
    {
        $this->factory = new NodeConverterFactory($valueNormalizer, $this);
    }

    public function convert(DiffNode $node): JsonDiffNode
    {
        return $this->factory->getStrategy($node->type)->convert($node);
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
