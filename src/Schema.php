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
     * @var array<int,SchemaAttributeInterface>
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

    public function load(): void
    {
        if (empty($this->schema)) {
            throw new InvalidSchemaException('Schema is empty');
        }

        $this->setName((string)\key($this->schema) ?? '');
        $this->setTitle($this->schema[$this->name()][Keyword::TITLE] ?? '');
        $this->setAttributes($this->schema[$this->name()][Keyword::ATTRIBUTES] ?? []);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if (empty(\trim($name))) {
            throw new InvalidSchemaException('Schema name is required');
        }

        $this->name = $name;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        if (empty(\trim($title))) {
            throw new InvalidSchemaException(\sprintf('Schema %s:title is required', $this->name()));
        }

        $this->title = $title;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        if (empty($attributes)) {
            throw new InvalidSchemaException(\sprintf('Schema %s:attributes are required', $this->name()));
        }

        $schemaAttributes = [];

        foreach ($attributes as $attribute) {
            $schemaAttribute = new SchemaAttribute($attribute);
            $schemaAttribute->validate();

            $schemaAttributes[] = $schemaAttribute;
        }

        $this->attributes = $schemaAttributes;
    }
}
