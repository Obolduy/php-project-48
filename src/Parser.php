<?php

namespace Hexlet\Code;

use Exception;

class Parser
{
    /**
     * @throws Exception
     */
    public function parseFile(string $pathToFile): array
    {
        $absolutePath = $this->getRealPath($pathToFile);

        if (!file_exists($absolutePath)) {
            throw new Exception("File not found: $pathToFile");
        }

        $data = json_decode(file_get_contents($absolutePath), true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in file: $pathToFile");
        }

        return $data;
    }

    private function getRealPath(string $path): string
    {
        if ($path[0] === '/') {
            return $path;
        }

        $realPath = realpath($path);

        if ($realPath === false) {
            return getcwd() . '/' . $path;
        }

        return $realPath;
    }
}

