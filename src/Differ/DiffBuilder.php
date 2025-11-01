<?php

namespace Hexlet\Code\Differ;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Nodes\Factories\DiffNodeFactory;

class DiffBuilder
{
    public function build(array $data1, array $data2): array
    {
        return $this->buildTree($data1, $data2);
    }

    /**
     * @return array<DiffNode>
     */
    private function buildTree(array $data1, array $data2): array
    {
        $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
        $sortedKeys = $this->getSortedKeys($allKeys);

        $tree = [];

        foreach ($sortedKeys as $key) {
            $tree[] = $this->buildNode($key, $data1, $data2);
        }

        return $tree;
    }

    /**
     * @param array<string> $keys
     * @return array<string>
     */
    private function getSortedKeys(array $keys): array
    {
        $sortedKeys = $keys;
        sort($sortedKeys);

        return $sortedKeys;
    }

    private function buildNode(string $key, array $data1, array $data2): DiffNode
    {
        $inFirst = array_key_exists($key, $data1);
        $inSecond = array_key_exists($key, $data2);

        $value1 = $inFirst ? $data1[$key] : null;
        $value2 = $inSecond ? $data2[$key] : null;

        $isNested = $this->isNested($value1, $value2);

        return match (true) {
            !$inFirst => DiffNodeFactory::createAdded($key, $value2),
            !$inSecond => DiffNodeFactory::createRemoved($key, $value1),
            $isNested => DiffNodeFactory::createNested(
                $key,
                $this->buildTree($this->toArray($value1), $this->toArray($value2))
            ),
            $value1 === $value2 => DiffNodeFactory::createUnchanged($key, $value1),
            default => DiffNodeFactory::createChanged($key, $value1, $value2),
        };
    }

    private function isNested(mixed $value1, mixed $value2): bool
    {
        return is_object($value1) && is_object($value2);
    }

    private function toArray(mixed $value): array
    {
        return is_object($value) ? get_object_vars($value) : $value;
    }
}
