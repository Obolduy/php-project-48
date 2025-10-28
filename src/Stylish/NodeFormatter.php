<?php

namespace Hexlet\Code\Stylish;

use Hexlet\Code\NodeFormatters\ChangedNodeFormatter;
use Hexlet\Code\NodeFormatters\LineFormatter;
use Hexlet\Code\NodeFormatters\NestedNodeFormatter;
use Hexlet\Code\NodeFormatters\SimpleNodeFormatter;
use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;

readonly class NodeFormatter
{
    private SimpleNodeFormatter $simpleFormatter;
    private ChangedNodeFormatter $changedFormatter;
    private NestedNodeFormatter $nestedFormatter;

    public function __construct(
        ValueFormatter $valueFormatter,
        IndentManager $indentManager
    ) {
        $lineFormatter = new LineFormatter($valueFormatter, $indentManager);

        $this->simpleFormatter = new SimpleNodeFormatter($lineFormatter);
        $this->changedFormatter = new ChangedNodeFormatter($lineFormatter);
        $this->nestedFormatter = new NestedNodeFormatter($indentManager);
    }

    public function format(DiffNode $node, int $depth): string
    {
        return match ($node->type) {
            DiffNodeTypeEnum::ADDED => $this->simpleFormatter->format($node, '+', $depth),
            DiffNodeTypeEnum::REMOVED => $this->simpleFormatter->format($node, '-', $depth),
            DiffNodeTypeEnum::UNCHANGED => $this->simpleFormatter->format($node, ' ', $depth),
            DiffNodeTypeEnum::CHANGED => $this->changedFormatter->format($node, $depth),
            DiffNodeTypeEnum::NESTED => $this->nestedFormatter->format(
                $node,
                $node->children,
                $depth,
                fn($children, $childDepth) => $this->formatChildren($children, $childDepth)
            ),
        };
    }

    /**
     * @param array<DiffNode> $children
     */
    private function formatChildren(array $children, int $depth): string
    {
        $lines = [];

        foreach ($children as $child) {
            $lines[] = $this->format($child, $depth);
        }

        return implode(ValueRepresentation::LINE_SEPARATOR->value, $lines);
    }
}
