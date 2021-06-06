<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Traits;

use FlexPHP\Schema\SchemaAttributeInterface;

trait AttributeHelperTrait
{
    private function isInt(SchemaAttributeInterface $property): bool
    {
        return \in_array($property->dataType(), ['smallint', 'integer', 'bigint']);
    }

    private function isDate(SchemaAttributeInterface $property): bool
    {
        return \strpos($property->typeHint(), '\Date') !== false;
    }

    private function isArray(SchemaAttributeInterface $property): bool
    {
        return \in_array($property->dataType(), ['array', 'simple_array', 'json']);
    }

    private function isNumeric(SchemaAttributeInterface $property): bool
    {
        return \in_array($property->dataType(), ['smallint', 'integer', 'bigint', 'double', 'float']);
    }

    private function isText(SchemaAttributeInterface $property): bool
    {
        return $property->dataType() === 'text';
    }

    private function isObject(SchemaAttributeInterface $property): bool
    {
        return $property->dataType() === 'object';
    }

    private function isString(SchemaAttributeInterface $property): bool
    {
        return $property->dataType() !== 'bigint' && $property->typeHint() === 'string';
    }

    private function isBinary(SchemaAttributeInterface $property): bool
    {
        return \in_array($property->dataType(), ['binary', 'bool', 'boolean', 'blob']);
    }

    private function hasLength(SchemaAttributeInterface $property): bool
    {
        return $property->minLength() !== null || $property->maxLength() !== null;
    }

    private function hasSize(SchemaAttributeInterface $property): bool
    {
        return $property->min() !== null || $property->max() !== null;
    }

    private function hasCheck(SchemaAttributeInterface $property): bool
    {
        return $property->minCheck() !== null || $property->maxCheck() !== null;
    }

    private function hasSizing(SchemaAttributeInterface $property): bool
    {
        return $this->hasSize($property) || $this->hasLength($property) || $this->hasCheck($property);
    }

    private function hasFormat(SchemaAttributeInterface $property): bool
    {
        return (bool)$property->format();
    }
}
