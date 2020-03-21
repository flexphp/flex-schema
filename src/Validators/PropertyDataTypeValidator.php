<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validators;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyDataTypeValidator
{
    public const ALLOWED_DATATYPES = [
        'smallint',
        'integer',
        'bigint',
        'decimal',
        'float',
        'string',
        'text',
        'guid',
        'binary',
        'blob',
        'boolean',
        'date',
        'datetime',
        'datetimetz',
        'time',
        'array',
        'json_array',
        'object',
    ];

    /**
     * @param string $dataType
     * @return ConstraintViolationListInterface
     */
    public function validate(string $dataType): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($dataType, [
            new Choice(self::ALLOWED_DATATYPES),
        ]);
    }
}
