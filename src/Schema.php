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
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Exception\InvalidFileSchemaException;
use FlexPHP\Schema\Exception\InvalidSchemaException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class Schema implements SchemaInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array<int,SchemaAttributeInterface>
     */
    private $attributes;

    /**
     * @var null|string
     */
    private $icon;

    /**
     * @var string
     */
    private $language;

    public static function fromArray(array $schema): SchemaInterface
    {
        /** @var string $name */
        $name = \key($schema) ?? '';
        $title = $schema[$name][Keyword::TITLE] ?? '';
        $attributes = $schema[$name][Keyword::ATTRIBUTES] ?? [];
        $icon = $schema[$name][Keyword::ICON] ?? null;
        $language = $schema[$name][Keyword::LANGUAGE] ?? null;

        return new self($name, $title, $attributes, $icon, $language);
    }

    public static function fromFile(string $schemafile): SchemaInterface
    {
        try {
            $yaml = new Yaml();
            $schema = $yaml->parseFile($schemafile);
        } catch (ParseException $e) {
            throw new InvalidFileSchemaException();
        }

        return self::fromArray($schema);
    }

    public function __construct(
        string $name,
        string $title,
        array $attributes,
        ?string $icon = null,
        ?string $language = null
    ) {
        $this->setName($name);
        $this->setTitle($title);
        $this->setAttributes($attributes);
        $this->setIcon($icon);
        $this->setLanguage($language);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function language(): string
    {
        return $this->language;
    }

    public function pkName(): string
    {
        $pkName = 'id';

        \array_filter($this->attributes(), function (SchemaAttributeInterface $property) use (&$pkName) {
            if ($property->isPk()) {
                $pkName = $property->name();
            }

            return true;
        });

        return $pkName;
    }

    public function pkTypeHint(): string
    {
        $pkTypeHint = 'string';

        \array_filter($this->attributes(), function (SchemaAttributeInterface $property) use (&$pkTypeHint) {
            if ($property->isPk()) {
                $pkTypeHint = $property->typeHint();
            }

            return true;
        });

        return $pkTypeHint;
    }

    public function fkRelations(): array
    {
        $fkRelations = \array_reduce(
            $this->attributes(),
            function (array $result, SchemaAttributeInterface $property): array {
                if ($property->isfk()) {
                    $result[$property->name()] = [
                        'pkTable' => $property->fkTable(),
                        'pkId' => $property->name(),
                        'pkDataType' => $property->dataType(),
                        'pkTypeHint' => $property->typeHint(),
                        'fkId' => $property->fkId(),
                        'fkName' => $property->fkName(),
                    ];
                }

                return $result;
            },
            []
        );

        return $fkRelations;
    }

    private function setName(string $name): void
    {
        if (empty(\trim($name))) {
            throw new InvalidSchemaException('Schema name is required');
        }

        $this->name = $name;
    }

    private function setTitle(string $title): void
    {
        if (empty(\trim($title))) {
            throw new InvalidSchemaException(\sprintf('Schema %s:title is required', $this->name()));
        }

        $this->title = $title;
    }

    private function setAttributes(array $attributes): void
    {
        $schemaAttributes = [];

        foreach ($attributes as $attribute) {
            if ($attribute instanceof SchemaAttributeInterface) {
                $schemaAttributes[] = $attribute;

                continue;
            }

            $name = $attribute[Keyword::NAME] ?? '';
            $dataType = $attribute[Keyword::DATATYPE] ?? '';
            $constraints = $attribute[Keyword::CONSTRAINTS] ?? '';

            try {
                $schemaAttributes[] = new SchemaAttribute($name, $dataType, $constraints);
            } catch (Exception $e) {
                throw new InvalidSchemaException(
                    \sprintf('Schema %s > %s', $this->name(), $e->getMessage())
                );
            }
        }

        $this->attributes = $schemaAttributes;
    }

    private function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    private function setLanguage(?string $language): void
    {
        if (empty($language)) {
            $language = 'en';
        }

        $this->language = $language;
    }
}
