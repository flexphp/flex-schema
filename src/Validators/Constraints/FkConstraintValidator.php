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

use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class FkConstraintValidator
{
    /**
     * @param mixed $string
     */
    public function validate($string): ConstraintViolationListInterface
    {
        if (($errors = $this->validateNotEmpty($string))->count()) {
            return $errors;
        }

        if (($errors = $this->validateCount($string))->count()) {
            return $errors;
        }

        return $this->validateRegex($string);
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

    /**
     * @param mixed $string
     */
    private function validateCount($string): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $parts = \is_string($string) ? \explode(',', $string) : $string;

        return $validator->validate($parts, [
            new Count([
                'min' => 1,
                'max' => 3,
                'minMessage' => 'Allow table[,name[,id]]',
                'maxMessage' => 'Allow table[,name[,id]]',
            ]),
        ]);
    }

    /**
     * @param mixed $string
     */
    private function validateRegex($string): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $string = \is_array($string) ? \implode(',', $string) : $string;

        return $validator->validate($string, [
            new Regex([
                'pattern' => '/^[a-zA-Z][a-zA-Z0-9_,]*$/',
                'message' => 'Characters not allowed. Use: a-Z, 0-9 and underscore (except in begin)',
            ]),
        ]);
    }
}
