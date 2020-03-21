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
use FlexPHP\Schema\Validations\SchemaAttributeValidation;

final class SchemaAttribute implements SchemaAttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $dataType;

    /**
     * @var array<string, mixed>
     */
    private $constraints = [];

    /**
     * @param mixed $constraints
     */
    public function __construct(string $name, string $dataType, $constraints = null)
    {
        $this->setName($name);
        $this->setDataType($dataType);
        $this->setConstraints($constraints);

        $this->validate();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function dataType(): string
    {
        return $this->dataType;
    }

    public function constraints(): array
    {
        return $this->constraints;
    }

    public function type(): ?string
    {
        return $this->constraints[Rule::TYPE] ?? null;
    }

    public function isRequired(): bool
    {
        return (bool)($this->constraints[Rule::REQUIRED] ?? null);
    }

    public function minLength(): ?int
    {
        return $this->constraints[Rule::MINLENGTH] ?? null;
    }

    public function maxLength(): ?int
    {
        return $this->constraints[Rule::MAXLENGTH] ?? null;
    }

    public function minCheck(): ?int
    {
        return $this->constraints[Rule::MINCHECK] ?? null;
    }

    public function maxCheck(): ?int
    {
        return $this->constraints[Rule::MAXCHECK] ?? null;
    }

    public function min(): ?int
    {
        return $this->constraints[Rule::MIN] ?? null;
    }

    public function max(): ?int
    {
        return $this->constraints[Rule::MAX] ?? null;
    }

    public function equalTo(): ?string
    {
        return $this->constraints[Rule::EQUALTO] ?? null;
    }

    private function validate(): void
    {
        $properties = $this->getProperties();

        (new SchemaAttributeValidation($properties))->validate();
    }

    private function getProperties(): array
    {
        return [
            Keyword::NAME => $this->name(),
            Keyword::DATATYPE => $this->dataType(),
            Keyword::CONSTRAINTS => $this->constraints(),
        ];
    }

    private function setName(string $name): void
    {
        $this->name = $name;
    }

    private function setDataType(string $dataType): void
    {
        $this->dataType = $dataType;
    }

    /**
     * @param mixed $constraints
     */
    private function setConstraints($constraints): void
    {
        if (!empty($constraints)) {
            if (\is_string($constraints)) {
                $this->setConstraintsFromString($constraints);
            } else {
                $this->setConstraintsFromArray($constraints);
            }
        }
    }

    private function setConstraintsFromString(string $constraints): void
    {
        $this->setConstraintsFromArray($this->getConstraintsFromString($constraints));
    }

    private function setConstraintsFromArray(array $constraints): void
    {
        $this->constraints = $this->getConstraintsCast($constraints);
    }

    private function getConstraintsFromString(string $constraints): array
    {
        $_constraints = \explode('|', $constraints);

        if (\count($_constraints)) {
            /** @var string $_constraint */
            foreach ($_constraints as $index => $_constraint) {
                $_rule = \explode(':', $_constraint);

                if (\count($_rule) === 2) {
                    [$_name, $_options] = $_rule;
                    $_constraints[$_name] = \preg_match('/^false$/i', $_options)
                        ? false
                        : $_options;
                } else {
                    $_constraints[$_rule[0]] = true;
                }

                unset($_constraints[$index]);
            }
        }

        return $_constraints;
    }

    private function getConstraintsCast(array $constraints): array
    {
        foreach ($constraints as $name => $value) {
            if (\is_int($name) && \is_string($value)) {
                $constraints[$value] = true;
                unset($constraints[$name]);
            } else {
                $constraints[$name] = \is_numeric($value) ? (int)$value : $value;
            }
        }

        return $constraints;
    }
}
