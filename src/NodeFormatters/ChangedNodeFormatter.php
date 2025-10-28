<?php

namespace Hexlet\Code\NodeFormatters;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Stylish\ValueRepresentation;

readonly class ChangedNodeFormatter
{
    public function __construct(
        private LineFormatter $lineFormatter
    ) {
    }

    public function format(DiffNode $node, int $depth): string
    {
        $oldLine = $this->lineFormatter->format('-', $node->key, $node->oldValue, $depth);
        $newLine = $this->lineFormatter->format('+', $node->key, $node->newValue, $depth);

        return $oldLine . ValueRepresentation::LINE_SEPARATOR->value . $newLine;
    }
}
