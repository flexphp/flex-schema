<?php

namespace FlexPHP\Schema\Validations;

use Exception;
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Exception\AttributeValidationException;
use FlexPHP\Schema\Validators\PropertyConstraintsValidator;
use FlexPHP\Schema\Validators\PropertyDataTypeValidator;
use FlexPHP\Schema\Validators\PropertyNameValidator;
use FlexPHP\Schema\Validators\PropertyTypeValidator;
use Symfony\Component\Validator\ConstraintViolationList;

class AttributeValidation implements ValidationInterface
{
    /**
     * @var array
     */
    protected $properties;

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
     * @var array
     */
    protected $requiredProperties = [
        Keyword::NAME,
        Keyword::DATATYPE,
    ];

    /**
     * @var array
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
        $notAllowedProperties = [];
        $requiredPropertiesNotPresent = [];

        foreach ($this->properties as $name => $value) {
            if (!\in_array($name, $this->allowedProperties)) {
                $notAllowedProperties[] = $name;
            }
        }

        if (!empty($notAllowedProperties)) {
            throw new AttributeValidationException('Properties unknow: ' . implode(', ', $notAllowedProperties));
        }

        foreach ($this->requiredProperties as $property) {
            if (!\in_array($property, array_keys($this->properties))) {
                $requiredPropertiesNotPresent[] = $property;
            }
        }

        if (!empty($requiredPropertiesNotPresent)) {
            throw new AttributeValidationException(
                'Required properties are missing: ' . implode(', ', $requiredPropertiesNotPresent)
            );
        }

        foreach ($this->properties as $property => $value) {
            if (in_array($property, array_keys($this->validators))) {
                $violations = $this->validateProperty($property, $value);

                if (0 !== count($violations)) {
                    throw new AttributeValidationException(sprintf("%1\$s:\n%2\$s", $property, $violations));
                }
            }
        }
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return ConstraintViolationList
     */
    private function validateProperty(string $property, $value): ConstraintViolationList
    {
        try {
            $validator = new $this->validators[$property];
            $violations = $validator->validate($value);
        } catch (Exception $e) {
            throw new AttributeValidationException(sprintf("%1\$s:\n%2\$s", $property, $e->getMessage()));
        }

        return $violations;
    }
}
