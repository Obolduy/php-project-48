<?php

namespace Hexlet\Code\Formatters;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Stylish\IndentManager;
use Hexlet\Code\Stylish\NodeFormatter;
use Hexlet\Code\Stylish\ValueFormatter;
use Hexlet\Code\Stylish\ValueRepresentation;

readonly class StylishFormatter implements FormatterInterface
{
    private const int DEFAULT_DEPTH = 1;

    private NodeFormatter $nodeFormatter;

    public function __construct()
    {
        $indentManager = new IndentManager();

        $this->nodeFormatter = new NodeFormatter(new ValueFormatter($indentManager), $indentManager);
    }

    /**
     * @param array<DiffNode> $tree
     */
    public function format(array $tree): string
    {
        return $this->formatWithDepth($tree, self::DEFAULT_DEPTH);
    }

    /**
     * @param array<DiffNode> $tree
     */
    private function formatWithDepth(array $tree, int $depth = 1): string
    {
        return ValueRepresentation::OPEN_BRACE->value .
               ValueRepresentation::LINE_SEPARATOR->value .
               $this->formatTree($tree, $depth) .
               ValueRepresentation::LINE_SEPARATOR->value .
               ValueRepresentation::CLOSE_BRACE->value;
    }

    /**
     * @param array<DiffNode> $tree
     */
    private function formatTree(array $tree, int $depth): string
    {
        $lines = [];

        foreach ($tree as $node) {
            $lines[] = $this->nodeFormatter->format($node, $depth);
        }

        return implode(ValueRepresentation::LINE_SEPARATOR->value, $lines);
    }
}
