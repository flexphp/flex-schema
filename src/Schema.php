<?php

namespace FlexPHP\Schema;

use FlexPHP\Schema\Exception\InvalidFileSchemaException;
use FlexPHP\Schema\Exception\InvalidSchemaException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Schema implements SchemaInterface
{
    private $schema;
    private $table;
    private $title;
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

        $table = key($this->schema) ?? null;

        if (!is_string($table)) {
            throw new InvalidSchemaException('Schema name must be a string');
        }

        $title = $this->schema[$table][Keyword::TITLE] ?? null;

        if (!is_string($title)) {
            throw new InvalidSchemaException("Schema {$table}:title must be a string");
        }

        $attributes = $this->schema[$table][Keyword::ATTRIBUTES] ?? null;

        if (!is_array($attributes)) {
            throw new InvalidSchemaException("Schema {$table}:attributes must be an array");
        }

        foreach($attributes as $name => $attribute) {
            if (!isset($attribute[Keyword::NAME])
                || !isset($attribute[Keyword::DATATYPE])
                || !isset($attribute[Keyword::CONSTRAINTS])
            ) {
                throw new InvalidSchemaException("Schema {$table}:attribute[$name] is invalid");
            }
        }

        $this->isValid = true;
        $this->table = $table;
        $this->title = $title;
        $this->attributes = $attributes;
    }

    public function table(): string
    {
        return $this->table;
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
