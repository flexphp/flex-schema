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

class TypeLogicValidator implements ValidationInterface
{
    use AttributeHelperTrait;

    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if ($this->property->isBlameAt() && !$this->isDate($this->property)) {
            throw new InvalidSchemaAttributeException('Blame At property must be date datatype.');
        }

        if ($this->property->isCa() && $this->property->isUa()) {
            throw new InvalidSchemaAttributeException('Created and Updated At in same property is not valid.');
        }

        if ($this->property->isBlameBy() && !$this->isInt($this->property)) {
            throw new InvalidSchemaAttributeException('Blame By property must be integer datatype.');
        }

        if ($this->property->isCb() && $this->property->isUb()) {
            throw new InvalidSchemaAttributeException('Created and Updated By in same property is not valid.');
        }

        if ($this->isNumeric($this->property) && $this->hasLength($this->property)) {
            throw new InvalidSchemaAttributeException('Numeric properties use: min, max.');
        }

        if ($this->isString($this->property) && $this->hasSize($this->property)) {
            throw new InvalidSchemaAttributeException('String properties use: minlength, maxlength.');
        }

        if (($this->isDate($this->property) || $this->isBinary($this->property)) && $this->hasSizing($this->property)) {
            throw new InvalidSchemaAttributeException('Date, bool, blob properties not use min, max, etc');
        }
    }
}
