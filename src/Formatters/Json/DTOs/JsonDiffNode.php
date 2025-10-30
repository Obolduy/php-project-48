<?php

namespace Hexlet\Code\Formatters\Json\DTOs;

use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;
use JsonSerializable;

readonly class JsonDiffNode implements JsonSerializable
{
    /**
     * @param array<JsonDiffNode>|null $children
     */
    public function __construct(
        public string $key,
        public DiffNodeTypeEnum $type,
        public mixed $value = null,
        public mixed $oldValue = null,
        public mixed $newValue = null,
        public ?array $children = null
    ) {
    }

    public function jsonSerialize(): array
    {
        $data = [
            'key' => $this->key,
            'type' => $this->type->value,
        ];

        $isChanged = $this->isChanged();

        if ($this->children === null && !$isChanged) {
            $data['value'] = $this->value;
        }

        if ($isChanged) {
            $data['oldValue'] = $this->oldValue;
            $data['newValue'] = $this->newValue;
        }

        if ($this->children !== null) {
            $data['children'] = $this->children;
        }

        return $data;
    }

    private function isChanged(): bool
    {
        return $this->type === DiffNodeTypeEnum::CHANGED;
    }
}
