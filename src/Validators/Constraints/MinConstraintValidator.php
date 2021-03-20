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

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class MinConstraintValidator
{
    /**
     * @param int $min
     */
    public function validate($min): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($min, [
            new NotBlank(),
            new Type('numeric'),
            new PositiveOrZero(),
        ]);
    }
}
