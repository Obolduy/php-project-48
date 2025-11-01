<?php

namespace Hexlet\Code\Stylish;

readonly class ValueFormatter
{
    public function __construct(
        private IndentManager $indentManager
    ) {
    }

    public function format(mixed $value, int $depth): string
    {
        return match (true) {
            is_bool($value) => $value ? ValueRepresentation::TRUE->value : ValueRepresentation::FALSE->value,
            is_null($value) => ValueRepresentation::NULL->value,
            is_object($value) => $this->formatArray(get_object_vars($value), $depth),
            is_array($value) => $this->formatArray($value, $depth),
            default => (string) $value,
        };
    }

    private function formatArray(array $array, int $depth): string
    {
        if ($array === []) {
            return ValueRepresentation::EMPTY_OBJECT->value;
        }

        $lines = [ValueRepresentation::OPEN_BRACE->value];
        $indent = $this->indentManager->makeArrayIndent($depth);

        foreach ($array as $key => $value) {
            $formattedValue = $this->format($value, $depth + 1);
            $lines[] = $indent . $key . ValueRepresentation::KEY_VALUE_SEPARATOR->value . $formattedValue;
        }

        $closingIndent = $this->indentManager->makeClosingIndent($depth);
        $lines[] = $closingIndent . ValueRepresentation::CLOSE_BRACE->value;

        return implode(ValueRepresentation::LINE_SEPARATOR->value, $lines);
    }
}
