<?php

namespace Hexlet\Code\NodeFormatters;

use Hexlet\Code\Nodes\DTOs\DiffNode;

readonly class SimpleNodeFormatter
{
    public function __construct(
        private LineFormatter $lineFormatter
    ) {
    }

    public function format(DiffNode $node, string $marker, int $depth): string
    {
        return $this->lineFormatter->format($marker, $node->key, $node->value, $depth);
    }
}
