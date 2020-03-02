<?php

namespace FlexPHP\Schema;

use FlexPHP\Schema\Exception\InvalidFileSchemaException;
use FlexPHP\Schema\Exception\InvalidSchemaException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Schema implements SchemaInterface
{
    /**
     * @var array<array>
     */
    private $schema;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array<array>
     */
    private $attributes;

    public function fromArray(array $schema): void
    {
        $this->schema = $schema;
    }

    public function fromFile(string $filename): void
    {
        try {
            $yaml = new Yaml();
            $schema = $yaml->parseFile($filename);
        } catch (ParseException $e) {
            throw new InvalidFileSchemaException();
        }

        $this->schema = $schema;
    }

    public function validate(): void
    {
        if (empty($this->schema)) {
            throw new InvalidSchemaException('Schema is empty');
        }

        $name = key($this->schema) ?? null;

        if (!is_string($name)) {
            throw new InvalidSchemaException('Schema name must be a string');
        }

        $title = $this->schema[$name][Keyword::TITLE] ?? null;

        if (!is_string($title)) {
            throw new InvalidSchemaException("Schema {$name}:title must be a string");
        }

        $attributes = $this->schema[$name][Keyword::ATTRIBUTES] ?? null;

        if (!is_array($attributes)) {
            throw new InvalidSchemaException("Schema {$name}:attributes must be an array");
        }

        foreach ($attributes as $attributeName => $attribute) {
            if (!isset($attribute[Keyword::NAME])
                || !isset($attribute[Keyword::DATATYPE])
                || !isset($attribute[Keyword::CONSTRAINTS])
            ) {
                throw new InvalidSchemaException("Schema {$attributeName}:attribute[$attributeName] is invalid");
            }
        }

        $this->name = $name;
        $this->title = $title;
        $this->attributes = $attributes;
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
}
