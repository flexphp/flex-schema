<?php declare(strict_types = 1);
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyNameValidator
{
    /**
     * @var int
     */
    private $minLength = 1;

    /**
     * @var int
     */
    private $maxLength = 64;

    public function validate(string $name): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($name, [
            new NotBlank(),
            new Length([
                'min' => $this->minLength,
                'max' => $this->maxLength,
            ]),
            new Regex([
                'pattern' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            ]),
        ]);
    }
}
