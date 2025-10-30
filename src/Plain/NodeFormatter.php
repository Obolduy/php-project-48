<?php

namespace Hexlet\Code\Plain;

use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;

readonly class NodeFormatter
{
    public function __construct(
        private MessageFormatter $messageFormatter,
        private PathBuilder $pathBuilder
    ) {
    }

    /**
     * @param array<DiffNode> $tree
     * @return array<string>
     */
    public function formatTree(array $tree, string $parentPath = ''): array
    {
        $lines = [];

        foreach ($tree as $node) {
            $currentPath = $this->pathBuilder->build($parentPath, $node->key);

            $result = match ($node->type) {
                DiffNodeTypeEnum::ADDED => $this->messageFormatter->formatAdded($currentPath, $node->value),
                DiffNodeTypeEnum::REMOVED => $this->messageFormatter->formatRemoved($currentPath),
                DiffNodeTypeEnum::CHANGED => $this->messageFormatter->formatUpdated(
                    $currentPath,
                    $node->oldValue,
                    $node->newValue
                ),
                DiffNodeTypeEnum::NESTED => $this->formatTree($node->children, $currentPath),
                DiffNodeTypeEnum::UNCHANGED => null,
            };

            if ($result !== null) {
                if (is_array($result)) {
                    $lines = array_merge($lines, $result);
                } else {
                    $lines[] = $result;
                }
            }
        }

        return $lines;
    }
}
