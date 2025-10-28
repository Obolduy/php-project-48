<?php

namespace Hexlet\Code;

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
     * @throws \Exception
     */
    public function generate(string $pathToFile1, string $pathToFile2): string
    {
        $data1 = $this->parser->parseFile($pathToFile1);
        $data2 = $this->parser->parseFile($pathToFile2);

        return $this->diffBuilder->build($data1, $data2);
    }
}
