<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validators\Constraints;

use FlexPHP\Schema\Constants\Format;
use FlexPHP\Schema\Constants\Operator;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class FormatConstraintValidator
{
    private const FORMATS = [
        Format::MONEY,
        Format::TIMEAGO,
        Format::DATETIME,
    ];

    /**
     * @param mixed $string
     */
    public function validate($string): ConstraintViolationListInterface
    {
        if (($errors = $this->validateNotEmpty($string))->count()) {
            return $errors;
        }

        return $this->validateValue($string);
    }

    /**
     * @param mixed $string
     */
    private function validateNotEmpty($string): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($string, [
            new NotBlank(),
        ]);
    }

    private function validateValue(string $string): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($string, [
            new Choice([
                'choices' => self::FORMATS,
                'message' => 'Allowed values are: ' . \implode(',', self::FORMATS),
            ]),
        ]);
    }
}
