<?php

namespace Hexlet\Code;

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

        if (!$inFirst) {
            return DiffNodeFactory::createAdded($key, $data2[$key]);
        }

        $inSecond = array_key_exists($key, $data2);

        if (!$inSecond) {
            return DiffNodeFactory::createRemoved($key, $data1[$key]);
        }

        $value1 = $data1[$key];
        $value2 = $data2[$key];

        if ($this->isAssociativeArray($value1) && $this->isAssociativeArray($value2)) {
            return DiffNodeFactory::createNested($key, $this->buildTree($value1, $value2));
        }

        if ($value1 === $value2) {
            return DiffNodeFactory::createUnchanged($key, $value1);
        }

        return DiffNodeFactory::createChanged($key, $value1, $value2);
    }

    private function isAssociativeArray(mixed $value): bool
    {
        return is_array($value) && !empty($value) && array_keys($value) !== range(0, count($value) - 1);
    }
}
