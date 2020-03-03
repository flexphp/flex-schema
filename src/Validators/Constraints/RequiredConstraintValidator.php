<?php declare(strict_types = 1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validators\Constraints;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class RequiredConstraintValidator
{
    /**
     * @param bool $bool
     * @return ConstraintViolationListInterface
     */
    public function validate($bool): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($bool, [
            new NotNull(),
            new Choice([true, false, 'true', 'false']),
        ]);
    }
}
