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
use FlexPHP\Schema\Constants\Rule;
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
     * @var array<mixed>
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

    public function isRequired(): bool
    {
        return (bool)($this->constraints[Rule::REQUIRED] ?? null);
    }

    public function minLength(): ?int
    {
        return isset($this->constraints[Rule::MINLENGTH])
            ? (int)$this->constraints[Rule::MINLENGTH]
            : null;
    }

    public function maxLength(): ?int
    {
        return isset($this->constraints[Rule::MAXLENGTH])
            ? (int)$this->constraints[Rule::MAXLENGTH]
            : null;
    }

    public function minCheck(): ?int
    {
        return isset($this->constraints[Rule::MINCHECK])
            ? (int)$this->constraints[Rule::MINCHECK]
            : null;
    }

    public function maxCheck(): ?int
    {
        return isset($this->constraints[Rule::MAXCHECK])
            ? (int)$this->constraints[Rule::MAXCHECK]
            : null;
    }

    public function min(): ?int
    {
        return isset($this->constraints[Rule::MIN])
            ? (int)$this->constraints[Rule::MIN]
            : null;
    }

    public function max(): ?int
    {
        return isset($this->constraints[Rule::MAX])
            ? (int)$this->constraints[Rule::MAX]
            : null;
    }

    public function equalTo(): ?string
    {
        return $this->constraints[Rule::EQUALTO] ?? null;
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
