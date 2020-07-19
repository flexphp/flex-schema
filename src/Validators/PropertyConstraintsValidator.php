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
use FlexPHP\Schema\Validators\Constraints\FkConstraintValidator;
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
        Rule::FK,
    ];

    /**
     * @var array
     */
    private $validators = [
        Rule::REQUIRED => RequiredConstraintValidator::class,
        Rule::MAX => MaxConstraintValidator::class,
        Rule::MAXLENGTH => MaxConstraintValidator::class,
        Rule::MAXCHECK => MaxConstraintValidator::class,
        Rule::MIN => MinConstraintValidator::class,
        Rule::MINLENGTH => MinConstraintValidator::class,
        Rule::MINCHECK => MinConstraintValidator::class,
        Rule::EQUALTO => EqualToConstraintValidator::class,
        Rule::TYPE => PropertyTypeValidator::class,
        Rule::LENGTH => RangeConstraintValidator::class,
        Rule::CHECK => RangeConstraintValidator::class,
        Rule::FK => FkConstraintValidator::class,
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
                new Choice([
                    'choices' => self::ALLOWED_RULES,
                    'message' => 'is not valid rule.',
                ]),
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
        return (new $this->validators[$rule])->validate($options);
    }
}
