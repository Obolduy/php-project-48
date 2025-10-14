<?php

namespace Hexlet\Code;

use Docopt;
use Exception;

class Application
{
    private array $config;
    private Differ $differ;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->differ = new Differ(new Parser());
    }

    public function run(): int
    {
        $arguments = Docopt::handle($this->config['doc']);

        if ($arguments['--help']) {
            return $this->handleHelp();
        }

        if ($arguments['--version']) {
            return $this->handleVersion();
        }

        return $this->handleDiff($arguments['<firstFile>'], $arguments['<secondFile>']);
    }

    private function handleHelp(): int
    {
        OutputFormatter::help($this->config['doc']);

        return 0;
    }

    private function handleVersion(): int
    {
        OutputFormatter::version($this->config['name'], $this->config['version']);

        return 0;
    }

    private function handleDiff(string $file1, string $file2): int
    {
        try {
            $result = $this->differ->genDiff($file1, $file2);
            
            Printer::print($result->file1, 1);
            Printer::print($result->file2, 2);

            return 0;
        } catch (Exception $e) {
            OutputFormatter::error($e->getMessage());

            return 1;
        }
    }
}

