<?php

namespace Hexlet\Code\Formatters\Json;

readonly class ValueNormalizer
{
    public function normalize(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        return $this->normalizeArray($value);
    }

    private function normalizeArray(array $value): array
    {
        return array_map(fn ($item) => $this->normalize($item), $value);
    }
}
