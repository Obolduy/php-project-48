<?php

namespace Hexlet\Code\Formatters;

use Hexlet\Code\Formatters\Enums\OutputFormatEnum;

class FormatterFactory
{
    public function getFormatter(OutputFormatEnum $format): FormatterInterface
    {
        return match ($format) {
            OutputFormatEnum::STYLISH => new StylishFormatter(),
            OutputFormatEnum::PLAIN => new PlainFormatter(),
            OutputFormatEnum::JSON => new JsonFormatter(),
        };
    }
}
