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

use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\Traits\AttributeHelperTrait;
use FlexPHP\Schema\Validations\ValidationInterface;

class DefaultLogicValidator implements ValidationInterface
{
    use AttributeHelperTrait;

    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        $default = $this->property->default();

        if ($default !== null) {
            if ($this->isString($this->property) && \is_bool($default)) {
                throw new InvalidSchemaAttributeException(
                    'String properties not allow default: NOW or boolean, used string or int values'
                );
            }

            if ($this->isText($this->property)) {
                throw new InvalidSchemaAttributeException('Text properties not allow default');
            }

            if ($this->isObject($this->property)) {
                throw new InvalidSchemaAttributeException('Object properties not allow default');
            }

            if ($this->isBinary($this->property)) {
                throw new InvalidSchemaAttributeException('Binary (bool, blob) properties not allow default');
            }

            if ($this->isArray($this->property)) {
                throw new InvalidSchemaAttributeException(
                    'Array (array, simple_array, json) properties not allow default',
                );
            }

            if ($this->isNumeric($this->property) && !\is_numeric($default)) {
                throw new InvalidSchemaAttributeException(\sprintf(
                    'Numeric properties not allow default: %s, use numeric values',
                    $default
                ));
            }

            if ($this->isDate($this->property) && $default !== 'NOW') {
                throw new InvalidSchemaAttributeException(\sprintf(
                    'Date property not allow default: %s, use null or "NOW" string',
                    $default
                ));
            }
        }
    }
}
