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

use FlexPHP\Schema\Constants\Action;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class ActionConstraintValidator
{
    private const ACTIONS = [
        Action::ALL,
        Action::INDEX,
        Action::CREATE,
        Action::READ,
        Action::UPDATE,
        Action::DELETE,
    ];

    /**
     * @param mixed $value
     */
    public function validate($value): ConstraintViolationListInterface
    {
        if (($errors = $this->validateType($value))->count()) {
            return $errors;
        }

        if (empty($value)) {
            $value = Action::ALL;
        }

        $invalid = $this->getInvalidActions($value);

        $validator = Validation::createValidator();

        return $validator->validate($invalid, [
            new Count([
                'max' => 0,
                'maxMessage' => 'Allowed values are: ' . \implode(',', self::ACTIONS),
            ]),
        ]);
    }

    private function validateType($value): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($value, [
            new Type([
                'type' => 'string',
                'message' => 'Logic: [it must be string type]',
            ]),
        ]);
    }

    private function getInvalidActions($value): array
    {
        $invalid = [];
        $actions = \explode(',', $value);

        $validator = Validation::createValidator();

        foreach ($actions as $action) {
            $errors = $validator->validate($action, [
                new Choice([
                    'choices' => self::ACTIONS,
                ]),
            ]);

            if (\count($errors)) {
                $invalid[] = $action;
            }
        }

        return $invalid;
    }
}
