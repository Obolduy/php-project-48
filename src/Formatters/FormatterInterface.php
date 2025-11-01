<?php

namespace Hexlet\Code\Formatters;

use Hexlet\Code\Nodes\DTOs\DiffNode;

interface FormatterInterface
{
    /**
     * @param array<DiffNode> $tree
     */
    public function format(array $tree): string;
}
