<?php

namespace Hexlet\Code\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements ParserInterface
{
    /**
     * @throws Exception
     */
    public function parse(string $content): array
    {
        try {
            return json_decode(json_encode(Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP)), true);
        } catch (Exception $e) {
            throw new Exception("Invalid YAML: " . $e->getMessage());
        }
    }
}
