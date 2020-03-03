<?php declare(strict_types = 1);
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
use FlexPHP\Schema\Validations\SchemaAttributeValidation;
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
            $yaml   = new Yaml();
            $schema = $yaml->parseFile($filename);
        } catch (ParseException $e) {
            throw new InvalidFileSchemaException();
        }

        $this->schema = $schema;
    }

    public function load(): void
    {
        if (empty($this->schema)) {
            throw new InvalidSchemaException('Schema is empty');
        }

        $this->setName((string) \key($this->schema) ?? null);
        $this->setTitle($this->schema[$this->name()][Keyword::TITLE] ?? null);
        $this->setAttributes($this->schema[$this->name()][Keyword::ATTRIBUTES] ?? null);
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

    private function setName(?string $name): void
    {
        if (!\is_string($name)) {
            throw new InvalidSchemaException('Schema name must be a string');
        }

        if (empty(\trim($name))) {
            throw new InvalidSchemaException('Schema name is required');
        }

        $this->name = $name;
    }

    private function setTitle(?string $title): void
    {
        if (!\is_string($title)) {
            throw new InvalidSchemaException(\sprintf('Schema %s:title must be a string', $this->name()));
        }

        if (empty(\trim($title))) {
            throw new InvalidSchemaException(\sprintf('Schema %s:title is required', $this->name()));
        }

        $this->title = $title;
    }

    private function setAttributes(?array $attributes): void
    {
        if (!\is_array($attributes)) {
            throw new InvalidSchemaException(\sprintf('Schema %s:attributes must be an array', $this->name()));
        }

        if (empty($attributes)) {
            throw new InvalidSchemaException(\sprintf('Schema %s:attributes are required', $this->name()));
        }

        foreach ($attributes as $attribute) {
            $validation = new SchemaAttributeValidation($attribute);
            $validation->validate();
        }

        $this->attributes = $attributes;
    }
}
