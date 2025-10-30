<?php

namespace Hexlet\Code\Plain;

readonly class ValueFormatter
{
    public function format(mixed $value): string
    {
        if (is_array($value)) {
            return PlainRepresentation::COMPLEX_VALUE->value;
        }

        if (is_bool($value)) {
            return $value ? PlainRepresentation::TRUE->value : PlainRepresentation::FALSE->value;
        }

        if (is_null($value)) {
            return PlainRepresentation::NULL->value;
        }

        if (is_string($value)) {
            return PlainRepresentation::PROPERTY_SUFFIX->value . $value . PlainRepresentation::PROPERTY_SUFFIX->value;
        }

        return (string) $value;
    }
}

