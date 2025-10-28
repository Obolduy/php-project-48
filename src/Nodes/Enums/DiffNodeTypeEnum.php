<?php

namespace Hexlet\Code\Nodes\Enums;

enum DiffNodeTypeEnum: string
{
    case ADDED = 'added';
    case REMOVED = 'removed';
    case UNCHANGED = 'unchanged';
    case CHANGED = 'changed';
    case NESTED = 'nested';
}
