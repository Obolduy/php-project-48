<?php

namespace Hexlet\Code;

use Exception;

class Differ
{
    private Parser $parser;

    private DiffBuilder $diffBuilder;

    public function __construct(Parser $parser, DiffBuilder $diffBuilder)
    {
        $this->parser = $parser;
        $this->diffBuilder = $diffBuilder;
    }

    /**
     * @throws Exception
     */
    public function generate(string $pathToFile1, string $pathToFile2): string
    {
        $content1 = $this->parser->parseFile($pathToFile1);
        $content2 = $this->parser->parseFile($pathToFile2);

        return $this->diffBuilder->build($content1, $content2);
    }
}

