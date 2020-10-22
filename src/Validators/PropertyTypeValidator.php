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
class PropertyTypeValidator
{
    public const ALLOWED_TYPES = [
        'text',
        'textarea',
        'email',
        'number',
        'integer',
        'digits',
        'alphanum',
        'url',
        'range',
        'pattern',
        'password',
        'timezone',
    ];

    public function validate(string $type): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($type, [
            new Choice([
                'choices' => self::ALLOWED_TYPES,
                'message' => 'is not valid type.',
            ]),
        ]);
    }
}
