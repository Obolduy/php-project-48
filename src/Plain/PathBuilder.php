<?php

namespace Hexlet\Code\Plain;

readonly class PathBuilder
{
    public function build(string $parentPath, string $key): string
    {
        if ($parentPath === '') {
            return $key;
        }

        return $parentPath . PlainRepresentation::PATH_SEPARATOR->value . $key;
    }
}
