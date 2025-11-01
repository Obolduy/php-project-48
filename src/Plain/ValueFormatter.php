<?php

namespace Hexlet\Code\Plain;

readonly class ValueFormatter
{
    public function format(mixed $value): string
    {
        return match (true) {
            is_array($value) => PlainRepresentation::COMPLEX_VALUE->value,
            is_bool($value) => $value ? PlainRepresentation::TRUE->value : PlainRepresentation::FALSE->value,
            is_null($value) => PlainRepresentation::NULL->value,
            is_string($value) => PlainRepresentation::PROPERTY_SUFFIX->value
                . $value
                . PlainRepresentation::PROPERTY_SUFFIX->value,
            default => (string) $value,
        };
    }
}
