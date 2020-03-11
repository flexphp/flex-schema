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
use Symfony\Component\Validator\Constraints\NotBlank;
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
     * @param mixed $constraints
     */
    public function validate($constraints): ConstraintViolationListInterface
    {
        $violations = new ConstraintViolationList();

        if (empty($constraints)) {
            return $violations;
        }

        $validator = Validation::createValidator();

        foreach ($constraints as $rule => $options) {
            if ($rule === '') {
                continue;
            }

            if (\is_string($options) && $options === Rule::REQUIRED) {
                $rule = $options;
                $options = true;
            }

            $errors = $validator->validate($rule, [
                new NotBlank(),
                new Choice(self::ALLOWED_RULES),
            ]);

            if (\count($errors) === 0) {
                $errors = $this->validateRule($rule, $options);
            }

            if (\count($errors) !== 0) {
                foreach ($errors as $error) {
                    $violations->add($error);
                }
            }
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
