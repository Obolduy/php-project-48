<?php

namespace Hexlet\Code\Diff;

class DiffFactory
{
    public static function createFromArray(array $doc): DiffDTO
    {
        $dto = new DiffDTO();

        $dto->file1 = $doc['file1'];
        $dto->file2 = $doc['file2'];

        return $dto;
    }
}

