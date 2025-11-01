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
        sort($allKeys);

        $tree = [];

        foreach ($allKeys as $key) {
            $tree[] = $this->buildNode($key, $data1, $data2);
        }

        return $tree;
    }

    private function buildNode(string $key, array $data1, array $data2): DiffNode
    {
        $inFirst = array_key_exists($key, $data1);
        $inSecond = array_key_exists($key, $data2);

        $value1 = $inFirst ? $data1[$key] : null;
        $value2 = $inSecond ? $data2[$key] : null;

        $isAssociativeArray = $this->isAssociativeArray($value1) && $this->isAssociativeArray($value2);

        return match (true) {
            !$inFirst => DiffNodeFactory::createAdded($key, $value2),
            !$inSecond => DiffNodeFactory::createRemoved($key, $value1),
            $isAssociativeArray => DiffNodeFactory::createNested($key, $this->buildTree($value1, $value2)),
            $value1 === $value2 => DiffNodeFactory::createUnchanged($key, $value1),
            default => DiffNodeFactory::createChanged($key, $value1, $value2),
        };
    }

    private function isAssociativeArray(mixed $value): bool
    {
        return is_array($value) && $value !== [] && array_keys($value) !== range(0, count($value) - 1);
    }
}
