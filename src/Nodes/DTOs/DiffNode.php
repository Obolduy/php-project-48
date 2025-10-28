<?php

namespace Hexlet\Code\Nodes\DTOs;

use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;

readonly class DiffNode
{
    /**
     * @param array<DiffNode> $children
     */
    public function __construct(
        public string $key,
        public DiffNodeTypeEnum $type,
        public mixed $value = null,
        public mixed $oldValue = null,
        public mixed $newValue = null,
        public array $children = []
    ) {
    }
}
