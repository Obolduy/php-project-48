<?php

namespace Hexlet\Code\NodeFormatters;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Stylish\IndentManager;
use Hexlet\Code\Stylish\ValueRepresentation;

readonly class NestedNodeFormatter
{
    public function __construct(
        private IndentManager $indentManager
    ) {
    }

    /**
     * @param DiffNode $node
     * @param array<DiffNode> $children
     * @param int $depth
     * @param callable $formatChildrenCallback
     * @return string
     */
    public function format(DiffNode $node, array $children, int $depth, callable $formatChildrenCallback): string
    {
        return $this->indentManager->makeIndent($depth) . $node->key . ValueRepresentation::KEY_VALUE_SEPARATOR->value .
               ValueRepresentation::OPEN_BRACE->value .
               ValueRepresentation::LINE_SEPARATOR->value .
               $formatChildrenCallback($children, $depth + 1) .
               ValueRepresentation::LINE_SEPARATOR->value .
               $this->indentManager->makeClosingIndent($depth) . ValueRepresentation::CLOSE_BRACE->value;
    }
}
