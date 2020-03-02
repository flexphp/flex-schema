<?php

namespace FlexPHP\Schema\Tests;

use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Schema;
use Symfony\Component\Yaml\Yaml;

class SchemaTest extends TestCase
{
    public function testItSchemaFromArrayEmptyThrowException()
    {
        $this->expectException(\ArgumentCountError::class);

        $schema = new Schema();
        $schema->fromArray();
    }

    public function testItSchemaFromArrayInvalidArgumentThrowException()
    {
        $this->expectException(\TypeError::class);

        $schema = new Schema();
        $schema->fromArray(null);
    }

    public function testItSchemaFromArrayEmptyNotThrowException()
    {
        $schema = new Schema();
        $schema->fromArray([]);

        $this->assertTrue(true);
    }

    public function testItSchemaFromArrayEmptyValidateThrowException()
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);

        $schema = new Schema();
        $schema->fromArray([]);
        $schema->validate();
    }

    public function testItSchemaFromArrayWithoutTableNameThrowException()
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':title must');

        $array = (new Yaml())->parseFile(sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::TITLE]);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->validate();
    }

    public function testItSchemaFromArrayWithoutTableAttributesThrowException()
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':attributes must');

        $array = (new Yaml())->parseFile(sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::ATTRIBUTES]);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->validate();
    }

    public function testItSchemaFromArrayWithTableAttributesInvalidThrowException()
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':attributes are invalid');

        $array = (new Yaml())->parseFile(sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::ATTRIBUTES]['column3'][Keyword::DATATYPE]);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->validate();
    }

    public function testItSchemaFromArrayOk()
    {
        $array = (new Yaml())->parseFile(sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->validate();

        $this->assertEquals('table', $schema->name());
        $this->assertEquals('Table Name', $schema->title());
        $this->assertIsArray($schema->attributes());
    }

    public function testItSchemaFromFileEmptyThrowException()
    {
        $this->expectException(\ArgumentCountError::class);

        $schema = new Schema();
        $schema->fromFile();
    }

    public function testItSchemaFromFileInvalidArgumentThrowException()
    {
        $this->expectException(\TypeError::class);

        $schema = new Schema();
        $schema->fromFile(null);
    }

    public function testItSchemaFromFileNotExistsThrowException()
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidFileSchemaException::class);

        $schema = new Schema();
        $schema->fromFile('/path/error');
    }

    public function testItSchemaFromFileFormatErrorThrowException()
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':attributes are invalid');

        $schema = new Schema();
        $schema->fromFile(sprintf('%s/../Mocks/yaml/error.yaml', __DIR__));
        $schema->validate();
    }

    public function testItSchemaFromFileOk()
    {
        $schema = new Schema();
        $schema->fromFile(sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $schema->validate();

        $this->assertEquals('table', $schema->name());
        $this->assertEquals('Table Name', $schema->title());
        $this->assertIsArray($schema->attributes());
    }
}
