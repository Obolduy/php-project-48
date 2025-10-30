<?php

namespace Hexlet\Code\Formatters\Json;

use Hexlet\Code\Formatters\Json\Strategies\AddedNodeConverter;
use Hexlet\Code\Formatters\Json\Strategies\ChangedNodeConverter;
use Hexlet\Code\Formatters\Json\Strategies\NestedNodeConverter;
use Hexlet\Code\Formatters\Json\Strategies\NodeConverterStrategyInterface;
use Hexlet\Code\Formatters\Json\Strategies\RemovedNodeConverter;
use Hexlet\Code\Formatters\Json\Strategies\UnchangedNodeConverter;
use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;

readonly class NodeConverterFactory
{
    /**
     * @var array<string, NodeConverterStrategyInterface>
     */
    private array $strategies;

    public function __construct(
        private ValueNormalizer $valueNormalizer,
        private NodeConverter $nodeConverter
    ) {
        $this->strategies = [
            DiffNodeTypeEnum::ADDED->value => new AddedNodeConverter($this->valueNormalizer),
            DiffNodeTypeEnum::REMOVED->value => new RemovedNodeConverter($this->valueNormalizer),
            DiffNodeTypeEnum::UNCHANGED->value => new UnchangedNodeConverter($this->valueNormalizer),
            DiffNodeTypeEnum::CHANGED->value => new ChangedNodeConverter($this->valueNormalizer),
            DiffNodeTypeEnum::NESTED->value => new NestedNodeConverter($this->nodeConverter),
        ];
    }

    public function getStrategy(DiffNodeTypeEnum $type): NodeConverterStrategyInterface
    {
        return $this->strategies[$type->value];
    }
}
