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
     * @var null|string
     */
    private $icon;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array<int,SchemaAttributeInterface>
     */
    private $attributes;

    public static function fromArray(array $schema): SchemaInterface
    {
        /** @var string $name */
        $name = \key($schema) ?? '';
        $title = $schema[$name][Keyword::TITLE] ?? '';
        $attributes = $schema[$name][Keyword::ATTRIBUTES] ?? [];
        $icon = $schema[$name][Keyword::ICON] ?? null;

        return new self($name, $title, $attributes, $icon);
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

    public function __construct(string $name, string $title, array $attributes, ?string $icon = null)
    {
        $this->setName($name);
        $this->setTitle($title);
        $this->setAttributes($attributes);
        $this->setIcon($icon);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    private function setName(string $name): void
    {
        if (empty(\trim($name))) {
            throw new InvalidSchemaException('Schema name is required');
        }

        $this->name = $name;
    }

    private function setIcon(?string $icon): void
    {
        $this->icon = $icon;
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
        if (empty($attributes)) {
            throw new InvalidSchemaException(\sprintf('Schema %s:attributes are required', $this->name()));
        }

        $schemaAttributes = [];

        foreach ($attributes as $attribute) {
            $name = $attribute[Keyword::NAME] ?? '';
            $dataType = $attribute[Keyword::DATATYPE] ?? '';
            $constraints = $attribute[Keyword::CONSTRAINTS] ?? '';

            $schemaAttributes[] = new SchemaAttribute($name, $dataType, $constraints);
        }

        $this->attributes = $schemaAttributes;
    }
}
