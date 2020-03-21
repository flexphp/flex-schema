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

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class RangeConstraintValidator
{
    public function validate(array $rule): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        if (($errors = (new MinConstraintValidator)->validate($rule['min'] ?? null))->count()) {
            return $errors;
        }

        if (($errors = (new MaxConstraintValidator)->validate($rule['max'] ?? null))->count()) {
            return $errors;
        }

        return $validator->validate($rule['max'], [
            new GreaterThanOrEqual($rule['min']),
        ]);
    }
}
