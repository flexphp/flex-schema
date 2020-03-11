<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Tests;

use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\SchemaAttribute;

class SchemaAttributeTest extends TestCase
{
    public function testItSchemaAttributeInvalidInvalidThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('is empty');

        $schemaAttribute = new SchemaAttribute();
        $schemaAttribute->validate();
    }

    public function testItSchemaAttributeWithIncompleteRequirePropertiesThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('are missing');

        $schemaAttribute = new SchemaAttribute();
        $schemaAttribute->setName('foo');
        $schemaAttribute->validate();
    }

    public function testItSchemaAttributeWithInvalidNamePropertiesThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('Name:');

        $schemaAttribute = new SchemaAttribute();
        $schemaAttribute->setName('');
        $schemaAttribute->setDataType('string');
        $schemaAttribute->validate();
    }

    public function testItSchemaAttributeWithInvalidDataTypePropertiesThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('DataType:');

        $schemaAttribute = new SchemaAttribute();
        $schemaAttribute->setName('foo');
        $schemaAttribute->setDataType('bar');
        $schemaAttribute->validate();
    }

    public function testItSchemaAttributeWithRequiredPropertiesSetValues(): void
    {
        $name = 'foo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute();
        $schemaAttribute->setName($name);
        $schemaAttribute->setDataType($dataType);
        $schemaAttribute->validate();

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
    }

    public function testItSchemaAttributePropertiesSetValues(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = 'required|min:8|max:10|type:number';
        $type = 'text';

        $schemaAttribute = new SchemaAttribute();
        $schemaAttribute->setName($name);
        $schemaAttribute->setDataType($dataType);
        $schemaAttribute->setConstraints($constraints);
        $schemaAttribute->setType($type);
        $schemaAttribute->validate();

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertEquals([
            'required' => true,
            'min' => 8,
            'max' => 10,
            'type' => 'number',
        ], $schemaAttribute->constraints());
        $this->assertEquals($type, $schemaAttribute->type());
    }
}
