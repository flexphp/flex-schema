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
use Symfony\Component\Validator\ConstraintViolationList;

class SchemaAttributeValidation implements ValidationInterface
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @var array
     */
    protected $requiredProperties = [
        Keyword::NAME,
        Keyword::DATATYPE,
    ];

    /**
     * @var array
     */
    private $allowedProperties = [
        Keyword::NAME,
        Keyword::DATATYPE,
        Keyword::TYPE,
        Keyword::CONSTRAINTS,
    ];

    /**
     * @var array<string>
     */
    private $validators = [
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

    public function validateRulesProperties(): void
    {
        foreach ($this->properties as $property => $value) {
            if (\in_array($property, \array_keys($this->validators))) {
                $violations = $this->validateProperty($property, $value);

                if (0 !== \count($violations)) {
                    throw new InvalidSchemaAttributeException(\sprintf("%1\$s:\n%2\$s", $property, $violations));
                }
            }
        }
    }

    public function validateAllowedProperties(): void
    {
        $notAllowedProperties = [];

        foreach ($this->properties as $name => $value) {
            if (!\in_array($name, $this->allowedProperties)) {
                $notAllowedProperties[] = $name;
            }
        }

        if (!empty($notAllowedProperties)) {
            throw new InvalidSchemaAttributeException('Properties unknow: ' . \implode(', ', $notAllowedProperties));
        }
    }

    public function validateRequiredProperties(): void
    {
        $requiredPropertiesNotPresent = [];

        foreach ($this->requiredProperties as $property) {
            if (!\in_array($property, \array_keys($this->properties))) {
                $requiredPropertiesNotPresent[] = $property;
            }
        }

        if (!empty($requiredPropertiesNotPresent)) {
            throw new InvalidSchemaAttributeException(
                'Required properties are missing: ' . \implode(', ', $requiredPropertiesNotPresent)
            );
        }
    }

    /**
     * @param mixed $value
     */
    private function validateProperty(string $property, $value): ConstraintViolationList
    {
        $validator = new $this->validators[$property];

        return $validator->validate($value);
    }
}