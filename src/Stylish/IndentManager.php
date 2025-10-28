<?php

namespace Hexlet\Code\Stylish;

class IndentManager
{
    private const int INDENT_SIZE = 4;
    private const int INDENT_OFFSET = 2;

    public function makeIndent(int $depth, string $marker = ' '): string
    {
        $spacesCount = $depth * self::INDENT_SIZE - self::INDENT_OFFSET;

        return str_repeat(' ', $spacesCount) . $marker . ' ';
    }

    public function makeClosingIndent(int $depth): string
    {
        return str_repeat(' ', $depth * self::INDENT_SIZE);
    }

    public function makeArrayIndent(int $depth): string
    {
        return str_repeat(' ', ($depth + 1) * self::INDENT_SIZE);
    }
}
