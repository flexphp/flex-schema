<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validations;

use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\Validators\PropertyConstraintsValidator;
use FlexPHP\Schema\Validators\PropertyDataTypeValidator;
use FlexPHP\Schema\Validators\PropertyNameValidator;
use FlexPHP\Schema\Validators\PropertyTypeValidator;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SchemaAttributeValidation implements ValidationInterface
{
    protected array $properties;

    protected array $requiredProperties = [
        Keyword::NAME,
        Keyword::DATATYPE,
    ];

    private array $allowedProperties = [
        Keyword::NAME,
        Keyword::DATATYPE,
        Keyword::TYPE,
        Keyword::CONSTRAINTS,
    ];

    /**
     * @var array<string>
     */
    private array $validators = [
        Keyword::NAME => PropertyNameValidator::class,
        Keyword::DATATYPE => PropertyDataTypeValidator::class,
        Keyword::TYPE => PropertyTypeValidator::class,
        Keyword::CONSTRAINTS => PropertyConstraintsValidator::class,
    ];

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function validate(): void
    {
        $this->validateAllowedProperties();

        $this->validateRequiredProperties();

        $this->validateRulesProperties();
    }

    private function validateAllowedProperties(): void
    {
        $notAllowedProperties = \array_filter(\array_keys($this->properties), function ($name) {
            return !\in_array($name, $this->allowedProperties);
        });

        if (!empty($notAllowedProperties)) {
            throw new InvalidSchemaAttributeException('Properties unknow: ' . \implode(', ', $notAllowedProperties));
        }
    }

    private function validateRequiredProperties(): void
    {
        $requiredProperties = \array_filter($this->requiredProperties, function ($requiredProperty) {
            return !array_key_exists($requiredProperty, $this->properties);
        });

        if (!empty($requiredProperties)) {
            throw new InvalidSchemaAttributeException(
                'Required properties are missing: ' . \implode(', ', $requiredProperties)
            );
        }
    }

    private function validateRulesProperties(): void
    {
        foreach ($this->properties as $property => $value) {
            if (array_key_exists($property, $this->validators)) {
                $violations = $this->validateProperty($property, $value);

                if (\count($violations) > 0) {
                    $valueProperty = \is_array($value) ? \json_encode($value) : $value;

                    throw new InvalidSchemaAttributeException(
                        \sprintf(
                            '%1$s: [%2$s] %3$s',
                            $property,
                            $valueProperty,
                            (string)$violations->get(0)->getMessage()
                        )
                    );
                }
            }
        }
    }

    /**
     * @param mixed $value
     */
    private function validateProperty(string $property, $value): ConstraintViolationListInterface
    {
        $validator = new $this->validators[$property];

        return $validator->validate($value);
    }
}
