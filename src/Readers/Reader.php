<?php

namespace Hexlet\Code\Readers;

use Hexlet\Code\Readers\Exceptions\ReaderException;

class Reader
{
    /**
     * @throws ReaderException
     */
    public function read(string $absolutePath, string $originalPath): string
    {
        $content = file_get_contents($absolutePath);

        if ($content === false) {
            throw new ReaderException("Failed to read file: $originalPath");
        }

        return $content;
    }
}
