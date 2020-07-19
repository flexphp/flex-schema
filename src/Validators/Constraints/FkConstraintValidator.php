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
        $validator = Validation::createValidator();

        if (($errors = $this->notEmpty($string))->count()) {
            return $errors;
        }

        $_vars = \is_string($string) ? \explode(',', $string) : $string;

        return $validator->validate($_vars, [
            new Count([
                'min' => 1,
                'max' => 3,
            ]),
        ]);
    }

    /**
     * @param mixed $string
     */
    private function notEmpty($string): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($string, [
            new NotBlank(),
        ]);
    }
}
