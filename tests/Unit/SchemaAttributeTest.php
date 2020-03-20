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

use FlexPHP\Schema\SchemaAttribute;

class SchemaAttributeTest extends TestCase
{
    public function testItSchemaAttributeWithInvalidNamePropertiesThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('Name:');

        new SchemaAttribute('', 'string');
    }

    public function testItSchemaAttributeWithInvalidDataTypePropertiesThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('DataType:');

        new SchemaAttribute('foo', 'bar');
    }

    public function testItSchemaAttributeWithRequiredPropertiesSetValues(): void
    {
        $name = 'foo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
    }

    public function testItSchemaAttributeSetConstratinsAsString(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = 'required|min:8|max:10|type:number';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertEquals([
            'required' => true,
            'min' => 8,
            'max' => 10,
            'type' => 'number',
        ], $schemaAttribute->constraints());
    }

    public function testItSchemaAttributeSetConstraintsAsArray(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = [
            'required' => true,
            'minlength' => 1,
            'maxlength' => '2',
            'mincheck' => 3,
            'maxcheck' => '4',
            'min' => 5,
            'max' => '6',
            'equalto' => '#foo',
            'type' => 'number',
        ];

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertEquals($constraints, $schemaAttribute->constraints());

        $this->assertTrue($schemaAttribute->isRequired());
        $this->assertEquals($constraints['minlength'], $schemaAttribute->minLength());
        $this->assertEquals($constraints['maxlength'], $schemaAttribute->maxLength());
        $this->assertEquals($constraints['mincheck'], $schemaAttribute->minCheck());
        $this->assertEquals($constraints['maxcheck'], $schemaAttribute->maxCheck());
        $this->assertEquals($constraints['min'], $schemaAttribute->min());
        $this->assertEquals($constraints['max'], $schemaAttribute->max());
        $this->assertEquals($constraints['equalto'], $schemaAttribute->equalTo());
        $this->assertEquals($constraints['type'], $schemaAttribute->type());
    }
}
