<?php

namespace Hexlet\Code\Parsers;

use Hexlet\Code\Parsers\Exceptions\YamlParserException;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements ParserInterface
{
    /**
     * @throws YamlParserException
     */
    public function parse(string $content): array
    {
        try {
            $yamlData = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            $jsonString = json_encode($yamlData);

            if ($jsonString === false) {
                throw new YamlParserException("Failed to encode YAML data to JSON: " . json_last_error_msg());
            }

            $result = json_decode($jsonString, true);

            if (!is_array($result)) {
                throw new YamlParserException("Failed to decode JSON or result is not an array");
            }

            return $result;
        } catch (YamlParserException $e) {
            throw new YamlParserException("Invalid YAML: " . $e->getMessage());
        }
    }
}
