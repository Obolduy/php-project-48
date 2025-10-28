<?php

namespace Hexlet\Code\Stylish;

enum ValueRepresentation: string
{
    case TRUE = 'true';
    case FALSE = 'false';
    case NULL = 'null';
    case EMPTY_OBJECT = '{}';
    case OPEN_BRACE = '{';
    case CLOSE_BRACE = '}';
    case KEY_VALUE_SEPARATOR = ': ';
    case LINE_SEPARATOR = "\n";
}
