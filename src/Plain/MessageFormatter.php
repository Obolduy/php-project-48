<?php

namespace Hexlet\Code\Plain;

readonly class MessageFormatter
{
    public function __construct(
        private ValueFormatter $valueFormatter
    ) {
    }

    public function formatAdded(string $path, mixed $value): string
    {
        return PlainRepresentation::PROPERTY_PREFIX->value .
               $path .
               PlainRepresentation::PROPERTY_SUFFIX->value .
               PlainRepresentation::ADDED_MESSAGE->value .
               $this->valueFormatter->format($value);
    }

    public function formatRemoved(string $path): string
    {
        return PlainRepresentation::PROPERTY_PREFIX->value .
               $path .
               PlainRepresentation::PROPERTY_SUFFIX->value .
               PlainRepresentation::REMOVED_MESSAGE->value;
    }

    public function formatUpdated(string $path, mixed $oldValue, mixed $newValue): string
    {
        return PlainRepresentation::PROPERTY_PREFIX->value .
               $path .
               PlainRepresentation::PROPERTY_SUFFIX->value .
               PlainRepresentation::UPDATED_MESSAGE_PREFIX->value .
               $this->valueFormatter->format($oldValue) .
               PlainRepresentation::UPDATED_MESSAGE_SEPARATOR->value .
               $this->valueFormatter->format($newValue);
    }
}

