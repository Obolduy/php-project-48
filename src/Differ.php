<?php

namespace Hexlet\Code;

use Exception;
use Hexlet\Code\Diff\DiffDTO;
use Hexlet\Code\Diff\DiffFactory;

class Differ
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @throws Exception
     */
    public function genDiff(string $pathToFile1, string $pathToFile2): DiffDTO
    {
        $content1 = $this->parser->parseFile($pathToFile1);
        $content2 = $this->parser->parseFile($pathToFile2);

        return DiffFactory::createFromArray([
            'file1' => $content1,
            'file2' => $content2,
        ]);
    }
}

