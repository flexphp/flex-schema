<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validators\Logics;

use FlexPHP\Schema\Constants\Format;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\Traits\AttributeHelperTrait;
use FlexPHP\Schema\Validations\ValidationInterface;

class FormatLogicValidator implements ValidationInterface
{
    use AttributeHelperTrait;

    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if ($this->hasFormat($this->property)) {
            if ($this->isString($this->property)) {
                throw new InvalidSchemaAttributeException('String properties not allow format');
            }

            if ($this->isText($this->property)) {
                throw new InvalidSchemaAttributeException('Text properties not allow default');
            }

            if ($this->isObject($this->property)) {
                throw new InvalidSchemaAttributeException('Object properties not allow format');
            }

            if ($this->isBinary($this->property)) {
                throw new InvalidSchemaAttributeException('Binary (binary, bool, blob) properties not allow format');
            }

            if ($this->isArray($this->property)) {
                throw new InvalidSchemaAttributeException(
                    'Array (array, simple_array, json) properties not allow format',
                );
            }

            if ($this->isNumeric($this->property) && !$this->property->isFormat(Format::MONEY)) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    'Numeric properties not allow format: %s',
                    $this->property->format()
                ));
            }

            if ($this->isDate($this->property) && $this->property->isFormat(Format::MONEY)) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    'Date property not allow format: %s',
                    $this->property->format()
                ));
            }
        }
    }
}
