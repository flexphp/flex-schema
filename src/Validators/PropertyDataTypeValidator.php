<?php

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
    const ALLOWED_DATATYPES = [
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
     * @param mixed $dataType
     * @return ConstraintViolationListInterface
     */
    public function validate($dataType): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($dataType, [
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
            new Choice(self::ALLOWED_DATATYPES),
        ]);
    }
}
