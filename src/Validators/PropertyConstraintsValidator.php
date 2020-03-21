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

use FlexPHP\Schema\Constants\Rule;
use FlexPHP\Schema\Validators\Constraints\EqualToConstraintValidator;
use FlexPHP\Schema\Validators\Constraints\MaxConstraintValidator;
use FlexPHP\Schema\Validators\Constraints\MinConstraintValidator;
use FlexPHP\Schema\Validators\Constraints\RangeConstraintValidator;
use FlexPHP\Schema\Validators\Constraints\RequiredConstraintValidator;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyConstraintsValidator
{
    public const ALLOWED_RULES = [
        Rule::REQUIRED,
        Rule::MINLENGTH,
        Rule::MAXLENGTH,
        Rule::LENGTH,
        Rule::MINCHECK,
        Rule::MAXCHECK,
        Rule::CHECK,
        Rule::MIN,
        Rule::MAX,
        Rule::EQUALTO,
        Rule::TYPE,
    ];

    /**
     * @param array<string, mixed> $constraints
     */
    public function validate(array $constraints): ConstraintViolationListInterface
    {
        $violations = new ConstraintViolationList();

        $validator = Validation::createValidator();

        foreach ($constraints as $rule => $options) {
            $errors = $validator->validate($rule, [
                new Choice(self::ALLOWED_RULES),
            ]);

            if (!\count($errors)) {
                $errors = $this->validateRule($rule, $options);
            }

            $violations->addAll($errors);
        }

        return $violations;
    }

    /**
     * @param mixed $options
     */
    private function validateRule(string $rule, $options): ConstraintViolationListInterface
    {
        $errors = new ConstraintViolationList();

        switch ($rule) {
            case Rule::REQUIRED:
                $errors = (new RequiredConstraintValidator())->validate($options);

                break;
            case Rule::MAX:
            case Rule::MAXLENGTH:
            case Rule::MAXCHECK:
                $errors = (new MaxConstraintValidator())->validate($options);

                break;
            case Rule::MIN:
            case Rule::MINLENGTH:
            case Rule::MINCHECK:
                $errors = (new MinConstraintValidator())->validate($options);

                break;
            case Rule::EQUALTO:
                $errors = (new EqualToConstraintValidator())->validate($options);

                break;
            case Rule::TYPE:
                $errors = (new PropertyTypeValidator())->validate($options);

                break;
            case Rule::LENGTH:
            case Rule::CHECK:
                $errors = (new RangeConstraintValidator())->validate($options);

                break;
        }

        return $errors;
    }
}
