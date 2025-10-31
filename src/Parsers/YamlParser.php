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
            $yamlData = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            $jsonString = json_encode($yamlData);

            if ($jsonString === false) {
                throw new Exception("Failed to encode YAML data to JSON: " . json_last_error_msg());
            }

            $result = json_decode($jsonString, true);

            if (!is_array($result)) {
                throw new Exception("Failed to decode JSON or result is not an array");
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception("Invalid YAML: " . $e->getMessage());
        }
    }
}
