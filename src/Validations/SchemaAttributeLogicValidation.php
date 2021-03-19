<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validations;

use FlexPHP\Schema\Constants\Format;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\SchemaAttributeInterface;

class SchemaAttributeLogicValidation implements ValidationInterface
{
    /**
     * @var SchemaAttributeInterface
     */
    private $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if (empty($this->property->constraints())) {
            return;
        }

        $name = 'Logic: [' . $this->property->name() . '] ';

        if ($this->property->isPk()) {
            if (!$this->property->isRequired()) {
                throw new InvalidSchemaAttributeException($name . 'Primary Key must be required.');
            }

            if ($this->property->isFk()) {
                throw new InvalidSchemaAttributeException($name . 'Primary Key cannot be Foreing Key too.');
            }

            if ($this->isInt() && !$this->property->isAi()) {
                throw new InvalidSchemaAttributeException($name . 'Primary Key numeric not autoincrement.');
            }

            if ($this->property->isAi() && $this->hasSizingConstraint()) {
                throw new InvalidSchemaAttributeException($name . 'Primary Key autoincrement cannot has sizing.');
            }
        }

        if ($this->property->isAi()) {
            if (!$this->property->isPk()) {
                throw new InvalidSchemaAttributeException($name . 'Autoincrement must be Primary Key too.');
            }

            if (!$this->isInt()) {
                throw new InvalidSchemaAttributeException($name . 'Autoincrement must be numeric.');
            }
        }

        if ($this->property->isBlameAt() && !$this->isDate()) {
            throw new InvalidSchemaAttributeException($name . 'Blame At property must be date datatype.');
        }

        if ($this->property->isCa() && $this->property->isUa()) {
            throw new InvalidSchemaAttributeException($name . 'Created and Updated At in same property is not valid.');
        }

        if ($this->property->isBlameBy() && !$this->isInt()) {
            throw new InvalidSchemaAttributeException($name . 'Blame By property must be integer datatype.');
        }

        if ($this->property->isCb() && $this->property->isUb()) {
            throw new InvalidSchemaAttributeException($name . 'Created and Updated By in same property is not valid.');
        }

        if ($this->isNumeric() && $this->hasLength()) {
            throw new InvalidSchemaAttributeException($name . 'Numeric properties use: min, max.');
        }

        if ($this->isString() && $this->hasSize()) {
            throw new InvalidSchemaAttributeException($name . 'String properties use: minlength, maxlength.');
        }

        if (($this->isDate() || $this->isBinary()) && $this->hasSizingConstraint()) {
            throw new InvalidSchemaAttributeException($name . 'Date, bool, blob properties not use min, max, etc');
        }

        if ($this->hasFormat()) {
            if ($this->isString()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sString properties not allow format',
                    $name
                ));
            }

            if ($this->isBinary()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sBinary (bool, blob) properties not allow format',
                    $name
                ));
            }

            if ($this->isArray()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sArray (array, simple_array, json) properties not allow format',
                    $name
                ));
            }

            if ($this->isNumeric() && !$this->property->isFormat(Format::MONEY)) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sNumeric properties not allow format: %s',
                    $name,
                    $this->property->format()
                ));
            }

            if ($this->isDate() && $this->property->isFormat(Format::MONEY)) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sDate property not allow format: %s',
                    $name,
                    $this->property->format()
                ));
            }
        }
    }

    private function isInt(): bool
    {
        return \in_array($this->property->dataType(), ['smallint', 'integer', 'bigint']);
    }

    private function isDate(): bool
    {
        return \strpos($this->property->typeHint(), '\Date') !== false;
    }

    private function isArray(): bool
    {
        return \in_array($this->property->dataType(), ['array', 'simple_array', 'json']);
    }

    private function isNumeric(): bool
    {
        return \in_array($this->property->dataType(), ['smallint', 'integer', 'bigint', 'double', 'float']);
    }

    private function isString(): bool
    {
        return $this->property->dataType() !== 'bigint' && $this->property->typeHint() === 'string';
    }

    private function isBinary(): bool
    {
        return \in_array($this->property->dataType(), ['bool', 'boolean', 'blob']);
    }

    private function hasLength(): bool
    {
        return $this->property->minLength() !== null || $this->property->maxLength() !== null;
    }

    private function hasSize(): bool
    {
        return $this->property->min() !== null || $this->property->max() !== null;
    }

    private function hasCheck(): bool
    {
        return $this->property->minCheck() !== null || $this->property->maxCheck() !== null;
    }

    private function hasSizingConstraint(): bool
    {
        return $this->hasSize() || $this->hasLength() || $this->hasCheck();
    }

    private function hasFormat(): bool
    {
        return (bool)$this->property->format();
    }
}
