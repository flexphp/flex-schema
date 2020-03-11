<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema;

use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\Validations\SchemaAttributeValidation;

class SchemaAttribute implements SchemaAttributeInterface
{
    /**
     * @var array<string>
     */
    private $properties = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $dataType;

    /**
     * @var array<array>
     */
    private $constraints;

    /**
     * @var string
     */
    private $type;

    public function __construct(array $properties = [])
    {
        if (!empty($properties)) {
            foreach ($properties as $name => $property) {
                if (\method_exists($this, 'set' . $name)) {
                    $this->{'set' . $name}($property);
                }
            }
        }
    }

    public function validate(): void
    {
        if (empty($this->properties)) {
            throw new InvalidSchemaAttributeException('Schema attribute is empty');
        }

        (new SchemaAttributeValidation($this->properties))->validate();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->properties[Keyword::NAME] = $this->name;
    }

    public function dataType(): string
    {
        return $this->dataType;
    }

    public function setDataType(string $dataType): void
    {
        $this->dataType = $dataType;
        $this->properties[Keyword::DATATYPE] = $this->dataType;
    }

    public function constraints(): array
    {
        return $this->constraints;
    }

    /**
     * Constraints can be array or string
     *
     * @param mixed $constraints
     */
    public function setConstraints($constraints): void
    {
        if (\is_string($constraints)) {
            $constraints = $this->getConstraintsFromString($constraints);
        }

        $this->constraints = $constraints;
        $this->properties[Keyword::CONSTRAINTS] = $this->constraints;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
        $this->properties[Keyword::TYPE] = $this->type;
    }

    private function getConstraintsFromString(string $constraints): array
    {
        $_constraints = \explode('|', $constraints);

        if (\count($_constraints) > 0) {
            /** @var string $_constraint */
            foreach ($_constraints as $index => $_constraint) {
                $_rule = \explode(':', $_constraint);

                if (\count($_rule) == 2) {
                    [$_name, $_options] = $_rule;
                    $_constraints[$_name] = $_options;
                } else {
                    $_constraints[$_rule[0]] = true;
                }

                unset($_constraints[$index]);
            }
        }

        return $_constraints;
    }
}
