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

        if ($this->property->isPk() && !$this->property->isRequired()) {
            throw new InvalidSchemaAttributeException($name . 'Primary Key must be required.');
        }

        if ($this->property->isAi() && !$this->property->isPk()) {
            throw new InvalidSchemaAttributeException($name . 'Autoincrement must be Primary Key too.');
        }

        if (!$this->property->isAi() && $this->property->isPk() && $this->isInt()) {
            throw new InvalidSchemaAttributeException($name . 'Primary Key numeric not autoincrement.');
        }

        if ($this->property->isAi() && !$this->isInt()) {
            throw new InvalidSchemaAttributeException($name . 'Autoincrement must be a numeric datatype.');
        }

        if ($this->property->isPk() && $this->property->isFk()) {
            throw new InvalidSchemaAttributeException($name . 'Primary Key cannot be Foreing Key too.');
        }

        if ($this->property->isAi() && $this->property->isFk()) {
            throw new InvalidSchemaAttributeException($name . 'Foreign Key cannot be autoincrement.');
        }

        if ($this->property->isBlame() && !$this->isDate()) {
            throw new InvalidSchemaAttributeException($name . 'Blame property must be date datetype valid.');
        }

        if ($this->property->isCa() && $this->property->isUa()) {
            throw new InvalidSchemaAttributeException($name . 'Created and Updated At in same property is not valid.');
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
    }

    private function isInt(): bool
    {
        return \in_array($this->property->dataType(), ['smallint', 'integer', 'bigint']);
    }

    private function isDate(): bool
    {
        return \strpos($this->property->typeHint(), '\Date') !== false;
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
        return \in_array($this->property->dataType(), ['bool', 'blob']);
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
}