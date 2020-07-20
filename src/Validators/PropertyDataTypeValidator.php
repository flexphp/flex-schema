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
        'double',
        'string',
        'text',
        'guid',
        'binary',
        'blob',
        'bool',
        'boolean',
        'date',
        'date_immutable',
        'datetime',
        'datetime_immutable',
        'datetimetz',
        'datetimetz_immutable',
        'time',
        'time_immutable',
        'array',
        'json_array',
        'object',
    ];

    public function validate(string $dataType): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($dataType, [
            new Choice([
                'choices' => self::ALLOWED_DATATYPES,
                'message' => 'is not valid datatype.',
            ]),
        ]);
    }
}
