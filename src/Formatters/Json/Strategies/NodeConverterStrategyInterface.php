<?php

namespace Hexlet\Code\Formatters\Json\Strategies;

use Hexlet\Code\Formatters\Json\DTOs\JsonDiffNode;
use Hexlet\Code\Nodes\DTOs\DiffNode;

interface NodeConverterStrategyInterface
{
    public function convert(DiffNode $node): JsonDiffNode;
}
