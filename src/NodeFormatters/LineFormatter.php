<?php

namespace Hexlet\Code\NodeFormatters;

use Hexlet\Code\Stylish\IndentManager;
use Hexlet\Code\Stylish\ValueFormatter;
use Hexlet\Code\Stylish\ValueRepresentation;

readonly class LineFormatter
{
    public function __construct(
        private ValueFormatter $valueFormatter,
        private IndentManager $indentManager
    ) {
    }

    public function format(string $marker, string $key, mixed $value, int $depth): string
    {
        $indent = $this->indentManager->makeIndent($depth, $marker);
        $formattedValue = $this->valueFormatter->format($value, $depth);

        return $indent . $key . ValueRepresentation::KEY_VALUE_SEPARATOR->value . $formattedValue;
    }
}
