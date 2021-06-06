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

class PkLogicValidator implements ValidationInterface
{
    use AttributeHelperTrait;

    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if ($this->property->isPk()) {
            if (!$this->property->isRequired()) {
                throw new InvalidSchemaAttributeException('Primary Key must be required.');
            }

            if ($this->property->isFk()) {
                throw new InvalidSchemaAttributeException('Primary Key cannot be Foreing Key too.');
            }

            if ($this->isInt($this->property) && !$this->property->isAi()) {
                throw new InvalidSchemaAttributeException('Primary Key numeric not autoincrement.');
            }

            if ($this->property->isAi() && $this->hasSizing($this->property)) {
                throw new InvalidSchemaAttributeException('Primary Key autoincrement cannot has sizing.');
            }
        }
    }
}
