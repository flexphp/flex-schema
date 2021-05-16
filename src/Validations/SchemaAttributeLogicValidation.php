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

use FlexPHP\Schema\Constants\Action;
use FlexPHP\Schema\Constants\Format;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\SchemaAttributeInterface;

class SchemaAttributeLogicValidation implements ValidationInterface
{
    private const ACTIONS = [
        Action::ALL,
        Action::INDEX,
        Action::CREATE,
        Action::READ,
        Action::UPDATE,
        Action::DELETE,
    ];

    private \FlexPHP\Schema\SchemaAttributeInterface $property;

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

            if ($this->isText()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sText properties not allow default',
                    $name
                ));
            }

            if ($this->isObject()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sObject properties not allow format',
                    $name
                ));
            }

            if ($this->isBinary()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sBinary (binary, bool, blob) properties not allow format',
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

        if ($this->property->fchars() && !$this->property->isFk()) {
            throw new InvalidSchemaAttributeException(\sprintf(
                '%sOnly property with Foreing Key allow fchars option',
                $name,
            ));
        }

        if ($this->property->fkcheck() && !$this->property->isFk()) {
            throw new InvalidSchemaAttributeException(\sprintf(
                '%sOnly property with Foreing Key allow fkcheck option',
                $name,
            ));
        }

        if ($this->property->usedInAll() && \count($this->property->show()) > 1) {
            throw new InvalidSchemaAttributeException(\sprintf(
                '%sShow constraint miss-configuration: ALL (a) option is exclusive',
                $name,
            ));
        }

        if ($this->property->usedInAll() && \count($this->property->hide()) > 1) {
            throw new InvalidSchemaAttributeException(\sprintf(
                '%sHide constraint miss-configuration: ALL (a) option is exclusive',
                $name,
            ));
        }

        foreach (self::ACTIONS as $action) {
            if (\in_array($action, $this->property->show()) && \in_array($action, $this->property->hide())) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sShow/Hide constraint miss-configuration: (' . $action . ') option is present in both',
                    $name,
                ));
            }
        }

        if (($default = $this->property->default()) !== null) {
            if ($this->isString() && (is_bool($default))) {
            // if ($this->isString() && ($default === 'NOW' || is_bool($default))) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sString properties not allow default: NOW or boolean, used string or int values',
                    $name
                ));
            }

            if ($this->isText()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sText properties not allow default',
                    $name
                ));
            }

            if ($this->isObject()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sObject properties not allow default',
                    $name
                ));
            }

            if ($this->isBinary()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sBinary (bool, blob) properties not allow default',
                    $name
                ));
            }

            if ($this->isArray()) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sArray (array, simple_array, json) properties not allow default',
                    $name
                ));
            }

            if ($this->isNumeric() && !is_numeric($default)) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sNumeric properties not allow default: %s, use numeric values',
                    $name,
                    $default
                ));
            }

            if ($this->isDate() && $default !== 'NOW') {
                throw new InvalidSchemaAttributeException(\sprintf(
                    '%sDate property not allow default: %s, use null or "NOW" string',
                    $name,
                    $default
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

    private function isText(): bool
    {
        return $this->property->dataType() === 'text';
    }

    private function isObject(): bool
    {
        return $this->property->dataType() === 'object';
    }

    private function isString(): bool
    {
        return $this->property->dataType() !== 'bigint' && $this->property->typeHint() === 'string';
    }

    private function isBinary(): bool
    {
        return \in_array($this->property->dataType(), ['binary', 'bool', 'boolean', 'blob']);
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
