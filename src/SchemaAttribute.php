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

use Exception;
use FlexPHP\Schema\Constants\Action;
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Constants\Rule;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\Validations\SchemaAttributeLogicValidation;
use FlexPHP\Schema\Validations\SchemaAttributeValidation;

final class SchemaAttribute implements SchemaAttributeInterface
{
    private string $name;

    private string $dataType;

    /**
     * @var array<string, mixed>
     */
    private array $constraints = [];

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
        return (bool)($this->constraints[Rule::REQUIRED] ?? false);
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

    public function isPk(): bool
    {
        return (bool)($this->constraints[Rule::PRIMARYKEY] ?? false);
    }

    public function isAi(): bool
    {
        return (bool)($this->constraints[Rule::AUTOINCREMENT] ?? false);
    }

    public function isFk(): bool
    {
        return (bool)($this->constraints[Rule::FOREIGNKEY] ?? false);
    }

    public function fkTable(): ?string
    {
        return $this->constraints[Rule::FOREIGNKEY]['table'] ?? null;
    }

    public function fkId(): ?string
    {
        return $this->constraints[Rule::FOREIGNKEY]['id'] ?? null;
    }

    public function fkName(): ?string
    {
        return $this->constraints[Rule::FOREIGNKEY]['name'] ?? null;
    }

    public function isCa(): bool
    {
        return (bool)($this->constraints[Rule::CREATEDAT] ?? false);
    }

    public function isUa(): bool
    {
        return (bool)($this->constraints[Rule::UPDATEDAT] ?? false);
    }

    public function isCb(): bool
    {
        return (bool)($this->constraints[Rule::CREATEDBY] ?? false);
    }

    public function isUb(): bool
    {
        return (bool)($this->constraints[Rule::UPDATEDBY] ?? false);
    }

    public function isBlameAt(): bool
    {
        return $this->isCa() || $this->isUa();
    }

    public function isBlameBy(): bool
    {
        return $this->isCb() || $this->isUb();
    }

    public function isBlame(): bool
    {
        return $this->isBlameAt() || $this->isBlameBy();
    }

    public function filter(): ?string
    {
        return $this->constraints[Rule::FILTER] ?? null;
    }

    public function format(): ?string
    {
        return $this->constraints[Rule::FORMAT] ?? null;
    }

    public function isFormat(string $format): bool
    {
        return $this->format() === $format;
    }

    public function trim(): bool
    {
        return (bool)($this->constraints[Rule::TRIM] ?? false);
    }

    public function fchars(): ?int
    {
        return $this->constraints[Rule::FCHARS] ?? null;
    }

    public function fkcheck(): bool
    {
        return (bool)($this->constraints[Rule::FKCHECK] ?? false);
    }

    public function link(): bool
    {
        return (bool)($this->constraints[Rule::LINK] ?? false);
    }

    public function show(): array
    {
        $default = $this->isBlame()
            ? Action::READ
            : Action::ALL;

        $hideConstraint = $this->constraints[Rule::HIDE] ?? '';

        if (\strpos($hideConstraint, Action::ALL) !== false) {
            $default = \str_replace(Action::ALL, '', $default);
        }

        if (\strpos($hideConstraint, Action::READ) !== false) {
            $default = \str_replace(Action::READ, '', $default);
        }

        return \explode(',', $this->constraints[Rule::SHOW] ?? $default);
    }

    public function hide(): array
    {
        $default = $this->isBlame()
            ? Action::INDEX . Action::CREATE . Action::UPDATE . Action::DELETE
            : '';

        return \explode(',', $this->constraints[Rule::HIDE] ?? $default);
    }

    /**
     * @return mixed
     */
    public function default()
    {
        if (!isset($this->constraints[Rule::DEFAULT])) {
            return null;
        }

        return $this->constraints[Rule::DEFAULT];
    }

    public function usedIn(string $action): bool
    {
        return \in_array($action, $this->show(), true);
    }

    public function properties(): array
    {
        return [
            Keyword::NAME => $this->name(),
            Keyword::DATATYPE => $this->dataType(),
            Keyword::CONSTRAINTS => $this->constraints(),
        ];
    }

    public function typeHint(): string
    {
        $typeHintByDataType = [
            'smallint' => 'int',
            'integer' => 'int',
            'float' => 'float',
            'double' => 'float',
            'bool' => 'bool',
            'boolean' => 'bool',
            'date' => '\DateTime',
            'date_immutable' => '\DateTimeImmutable',
            'datetime' => '\DateTime',
            'datetime_immutable' => '\DateTimeImmutable',
            'datetimetz' => '\DateTime',
            'datetimetz_immutable' => '\DateTimeImmutable',
            'time' => '\DateTime',
            'time_immutable' => '\DateTimeImmutable',
            'array' => 'array',
            'simple_array' => 'array',
            'json' => 'array',
        ];

        if (isset($typeHintByDataType[$this->dataType()])) {
            return $typeHintByDataType[$this->dataType()];
        }

        return 'string';
    }

    private function validate(): void
    {
        try {
            (new SchemaAttributeValidation($this->properties()))->validate();
            (new SchemaAttributeLogicValidation($this))->validate();
        } catch (Exception $exception) {
            throw new InvalidSchemaAttributeException(
                \sprintf('Attribute %s > %s', $this->name(), $exception->getMessage())
            );
        }
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

        /** @var mixed $_constraint */
        foreach ($_constraints as $index => $_constraint) {
            $_rule = \explode(':', $_constraint);

            if (\count($_rule) === 2) {
                [$_name, $_options] = $_rule;

                if (Rule::FOREIGNKEY !== $_name && \strpos($_options, ',') !== false) { // Range
                    [$min, $max] = \explode(',', $_options);
                    $_options = ['min' => $min, 'max' => $max];
                } elseif (\preg_match('#^false$#i', $_options)) { // False as string
                    $_options = false;
                } elseif (\preg_match('#^true$#i', $_options)) { // True as string
                    $_options = true;
                }

                $_constraints[$_name] = $_options;
            } else {
                $_constraints[$_rule[0]] = true;
            }

            unset($_constraints[$index]);
        }

        return $_constraints;
    }

    private function getConstraintsCast(array $constraints): array
    {
        foreach ($constraints as $name => $value) {
            if (\is_int($name)) {
                $constraints[$value] = true;
                unset($constraints[$name]);
            } elseif ($name === Rule::CHECK || $name === Rule::LENGTH) {
                $constraints['min' . $name] = (int)$value['min'];
                $constraints['max' . $name] = (int)$value['max'];
                unset($constraints[$name]);
            } elseif ($name === Rule::FOREIGNKEY && \is_string($value)) {
                $constraints[$name] = $this->getFkOptions($value);
            } elseif ($name === Rule::DEFAULT && \is_string($value) && \preg_match('#^\d.\d#', $value)) {
                $constraints[$name] = (float)$value;
            } else {
                $constraints[$name] = \is_numeric($value) ? (int)$value : $value;
            }
        }

        return $constraints;
    }

    private function getFkOptions(string $constraint): array
    {
        $_vars = \explode(',', $constraint);
        $fkName = 'name';
        $fkId = 'id';

        switch (\count($_vars)) {
            case 3:
                [$fkTable, $fkName, $fkId] = $_vars;

                break;
            case 2:
                [$fkTable, $fkName] = $_vars;

                break;
            default:
                [$fkTable] = $_vars;

                break;
        }

        return [
            'table' => $fkTable,
            'name' => $fkName,
            'id' => $fkId,
        ];
    }
}
