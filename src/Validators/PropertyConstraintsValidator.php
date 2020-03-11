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
        'required',
        'minlength',
        'maxlength',
        'length',
        'mincheck',
        'maxcheck',
        'check',
        'min',
        'max',
        'equalto',
        'type',
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

            if (\is_string($options) && $options == 'required') {
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
            case 'required':
                $errors = (new RequiredConstraintValidator())->validate($options);

                break;
            case 'max':
            case 'maxlength':
            case 'maxcheck':
                $errors = (new MaxConstraintValidator())->validate($options);

                break;
            case 'min':
            case 'minlength':
            case 'mincheck':
                $errors = (new MinConstraintValidator())->validate($options);

                break;
            case 'equalto':
                $errors = (new EqualToConstraintValidator())->validate($options);

                break;
            case 'type':
                $errors = (new PropertyTypeValidator())->validate($options);

                break;
            case 'length':
            case 'check':
                $errors = (new RangeConstraintValidator())->validate($options);

                break;
        }

        return $errors;
    }
}
