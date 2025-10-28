<?php

namespace Hexlet\Code\Nodes\Factories;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;

class DiffNodeFactory
{
    public static function createAdded(string $key, mixed $value): DiffNode
    {
        return new DiffNode(
            key: $key,
            type: DiffNodeTypeEnum::ADDED,
            value: $value
        );
    }

    public static function createRemoved(string $key, mixed $value): DiffNode
    {
        return new DiffNode(
            key: $key,
            type: DiffNodeTypeEnum::REMOVED,
            value: $value
        );
    }

    public static function createUnchanged(string $key, mixed $value): DiffNode
    {
        return new DiffNode(
            key: $key,
            type: DiffNodeTypeEnum::UNCHANGED,
            value: $value
        );
    }

    public static function createChanged(string $key, mixed $oldValue, mixed $newValue): DiffNode
    {
        return new DiffNode(
            key: $key,
            type: DiffNodeTypeEnum::CHANGED,
            oldValue: $oldValue,
            newValue: $newValue
        );
    }

    /**
     * @param array<DiffNode> $children
     */
    public static function createNested(string $key, array $children): DiffNode
    {
        return new DiffNode(
            key: $key,
            type: DiffNodeTypeEnum::NESTED,
            children: $children
        );
    }
}
