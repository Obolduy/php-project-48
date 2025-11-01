<?php

namespace Hexlet\Code\Formatters;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Plain\MessageFormatter;
use Hexlet\Code\Plain\NodeFormatter;
use Hexlet\Code\Plain\PathBuilder;
use Hexlet\Code\Plain\ValueFormatter;

readonly class PlainFormatter implements FormatterInterface
{
    private NodeFormatter $nodeFormatter;

    public function __construct()
    {
        $valueFormatter = new ValueFormatter();

        $this->nodeFormatter = new NodeFormatter(new MessageFormatter($valueFormatter), new PathBuilder());
    }

    /**
     * @param array<DiffNode> $tree
     */
    public function format(array $tree): string
    {
        return implode("\n", $this->nodeFormatter->formatTree($tree));
    }
}
