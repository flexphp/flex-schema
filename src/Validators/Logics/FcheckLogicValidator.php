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
use FlexPHP\Schema\Validations\ValidationInterface;

class FcheckLogicValidator implements ValidationInterface
{
    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if ($this->property->fkcheck() && !$this->property->isFk()) {
            throw new InvalidSchemaAttributeException('Only property with Foreing Key allow fkcheck option');
        }
    }
}
