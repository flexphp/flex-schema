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

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyNameValidator
{
    private int $minLength = 1;

    private int $maxLength = 64;

    public function validate(string $name): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($name, [
            new Length([
                'min' => $this->minLength,
                'max' => $this->maxLength,
                'allowEmptyString' => false,
                'minMessage' => 'must be least {{ limit }} characters long.',
                'maxMessage' => 'must be max {{ limit }} characters.',
            ]),
            new Regex([
                'pattern' => '/^(?=[^_0-9].*)\w*[^_\W]$/',
                'message' => 'is not valid. Only accept letters, numbers and underscore (except at beginning nor end).',
            ]),
        ]);
    }
}
