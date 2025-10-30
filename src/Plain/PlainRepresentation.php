<?php

namespace Hexlet\Code\Plain;

enum PlainRepresentation: string
{
    case PROPERTY_PREFIX = "Property '";
    case PROPERTY_SUFFIX = "'";
    case ADDED_MESSAGE = " was added with value: ";
    case REMOVED_MESSAGE = " was removed";
    case UPDATED_MESSAGE_PREFIX = " was updated. From ";
    case UPDATED_MESSAGE_SEPARATOR = " to ";
    case COMPLEX_VALUE = '[complex value]';
    case TRUE = 'true';
    case FALSE = 'false';
    case NULL = 'null';
    case PATH_SEPARATOR = '.';
}
