<?php

namespace Hexlet\Code;

use function Funct\Collection\sortBy;

class DiffBuilder
{
    public function build(array $data1, array $data2): string
    {
        $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
        
        $allKeys = sortBy($allKeys, fn($key) => $key);
        
        $lines = ['{'];
        
        foreach ($allKeys as $key) {
            $inFirst = array_key_exists($key, $data1);
            $inSecond = array_key_exists($key, $data2);
            
            if ($inFirst && $inSecond) {
                if ($data1[$key] === $data2[$key]) {
                    $lines[] = $this->formatLine(' ', $key, $data1[$key]);
                } else {
                    $lines[] = $this->formatLine('-', $key, $data1[$key]);
                    $lines[] = $this->formatLine('+', $key, $data2[$key]);
                }
            } elseif ($inFirst) {
                $lines[] = $this->formatLine('-', $key, $data1[$key]);
            } else {
                $lines[] = $this->formatLine('+', $key, $data2[$key]);
            }
        }
        
        $lines[] = '}';
        
        return implode("\n", $lines);
    }
    
    private function formatLine(string $marker, string $key, mixed $value): string
    {
        return "  $marker $key: {$this->formatValue($value)}";
    }
    
    private function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        
        if (is_null($value)) {
            return 'null';
        }
        
        if (is_string($value)) {
            return $value;
        }
        
        return (string) $value;
    }
}


