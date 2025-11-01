<?php

namespace Hexlet\Code;

use Docopt;
use Exception;
use Hexlet\Code\Differ\DiffBuilder;
use Hexlet\Code\Differ\Differ;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Formatters\Exceptions\JsonFormatterException;
use Hexlet\Code\Formatters\OutputFormatter;
use Hexlet\Code\Parsers\Common\Parser;

class Application
{
    private array $config;

    private Differ $differ;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->differ = new Differ(new Parser(), new DiffBuilder());
    }

    public function run(): int
    {
        $arguments = Docopt::handle($this->config['doc']);

        if ($arguments['--help'] === true) {
            return $this->handleHelp();
        }

        if ($arguments['--version'] === true) {
            return $this->handleVersion();
        }

        $formatString = $arguments['--format'] ?? OutputFormatEnum::STYLISH->value;
        $this->config['format'] = OutputFormatEnum::tryFrom($formatString) ?? OutputFormatEnum::STYLISH;

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
            $format = $this->config['format'] ?? OutputFormatEnum::STYLISH;

            $diff = $this->differ->generate($file1, $file2, $format);

            echo $diff . PHP_EOL;

            return 0;
        } catch (Exception | JsonFormatterException $e) {
            OutputFormatter::error($e->getMessage());

            return 1;
        }
    }
}
