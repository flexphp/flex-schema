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

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
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

        return $validator->validate($rule, new Collection([
            'min' => [
                new NotBlank(),
                new PositiveOrZero(),
            ],
            'max' => [
                new NotBlank(),
                new Positive(),
                new GreaterThanOrEqual($rule['min'] ?? 0),
            ],
        ]));
    }
}
