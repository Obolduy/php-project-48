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
        if (is_bool($value)) {
            return $value ? ValueRepresentation::TRUE->value : ValueRepresentation::FALSE->value;
        }

        if (is_null($value)) {
            return ValueRepresentation::NULL->value;
        }

        if (is_array($value)) {
            return $this->formatArray($value, $depth);
        }

        return (string) $value;
    }

    private function formatArray(array $array, int $depth): string
    {
        if (empty($array)) {
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
