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

    /**
     * @dataProvider getRequiredConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeRequiredConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isRequired());
    }

    /**
     * @dataProvider getMinLengthConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeMinLengthConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->minLength());
    }

    /**
     * @dataProvider getMinConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeMinConstraints($constraint, $expected): void
    {
        $name = 'min';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->min());
    }

    /**
     * @dataProvider getMaxLengthConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeMaxLengthConstraints($constraint, $expected): void
    {
        $name = 'minLength';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->maxLength());
    }

    /**
     * @dataProvider getMaxConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeMaxConstraints($constraint, $expected): void
    {
        $name = 'max';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->max());
    }

    /**
     * @dataProvider getMinCheckConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeMinCheckConstraints($constraint, $expected): void
    {
        $name = 'minCheck';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->minCheck());
    }

    /**
     * @dataProvider getMaxCheckConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeMaxCheckConstraints($constraint, $expected): void
    {
        $name = 'maxCheck';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->maxCheck());
    }

    /**
     * @dataProvider getCheckConstraint
     *
     * @param mixed $constraint
     * @param mixed $expectedMin
     * @param mixed $expectedMax
     */
    public function testItSchemaAttributeCheckConstraints($constraint, $expectedMin, $expectedMax): void
    {
        $name = 'check';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expectedMin, $schemaAttribute->minCheck());
        $this->assertSame($expectedMax, $schemaAttribute->maxCheck());
    }

    /**
     * @dataProvider getLengthConstraint
     *
     * @param mixed $constraint
     * @param mixed $expectedMin
     * @param mixed $expectedMax
     */
    public function testItSchemaAttributeLengthConstraints($constraint, $expectedMin, $expectedMax): void
    {
        $name = 'length';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expectedMin, $schemaAttribute->minLength());
        $this->assertSame($expectedMax, $schemaAttribute->maxLength());
    }

    /**
     * @dataProvider getEqualToConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeEqualToConstraints($constraint, $expected): void
    {
        $name = 'equalTo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->equalTo());
    }

    /**
     * @dataProvider getTypeConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeTypeConstraints($constraint, $expected): void
    {
        $name = 'equalTo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->type());
    }

    /**
     * @dataProvider getFkConstraint
     *
     * @param mixed $constraint
     * @param mixed $fkTable
     * @param mixed $fkId
     * @param mixed $fkName
     */
    public function testItSchemaAttributeFkConstraints($constraint, $fkTable, $fkId, $fkName): void
    {
        $name = 'foreing';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertTrue($schemaAttribute->isFk());
        $this->assertSame($fkTable, $schemaAttribute->fkTable());
        $this->assertSame($fkId, $schemaAttribute->fkId());
        $this->assertSame($fkName, $schemaAttribute->fkName());
    }

    public function testItSchemaAttributeConstraintsAsString(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = 'required|min:1|minlength:8|max:100|maxlength:10|mincheck:3|maxcheck:4|equalto:#bar|type:number';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertIsArray($schemaAttribute->constraints());
        $this->assertSame(true, $schemaAttribute->isRequired());
        $this->assertSame(1, $schemaAttribute->min());
        $this->assertSame(8, $schemaAttribute->minLength());
        $this->assertSame(3, $schemaAttribute->minCheck());
        $this->assertSame(100, $schemaAttribute->max());
        $this->assertSame(10, $schemaAttribute->maxLength());
        $this->assertSame(4, $schemaAttribute->maxCheck());
        $this->assertSame('#bar', $schemaAttribute->equalTo());
        $this->assertSame('number', $schemaAttribute->type());
        $this->assertFalse($schemaAttribute->isFk());
        $this->assertNull($schemaAttribute->fkTable());
        $this->assertNull($schemaAttribute->fkId());
        $this->assertNull($schemaAttribute->fkName());
    }

    public function testItSchemaAttributeConstraintsAsArray(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = [
            'required',
            'min' => 1,
            'minlength' => 8,
            'max' => 100,
            'maxlength' => 10,
            'mincheck' => 3,
            'maxcheck' => 4,
            'equalto' => '#id',
            'type' => 'text',
            'fk' => 'table,name,id',
        ];

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertIsArray($schemaAttribute->constraints());
        $this->assertSame(true, $schemaAttribute->isRequired());
        $this->assertSame(1, $schemaAttribute->min());
        $this->assertSame(8, $schemaAttribute->minLength());
        $this->assertSame(3, $schemaAttribute->minCheck());
        $this->assertSame(100, $schemaAttribute->max());
        $this->assertSame(10, $schemaAttribute->maxLength());
        $this->assertSame(4, $schemaAttribute->maxCheck());
        $this->assertSame('#id', $schemaAttribute->equalTo());
        $this->assertSame('text', $schemaAttribute->type());
        $this->assertTrue($schemaAttribute->isFk());
        $this->assertSame('table', $schemaAttribute->fkTable());
        $this->assertSame('id', $schemaAttribute->fkId());
        $this->assertSame('name', $schemaAttribute->fkName());
    }

    public function testItSchemaAttributeConstraintsAsArrayCast(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = [
            'required',
            'min' => '1',
            'minlength' => '8',
            'max' => '100',
            'maxlength' => '10',
            'mincheck' => '3',
            'maxcheck' => '4',
            'equalto' => '#bar',
            'type' => 'number',
        ];

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertIsArray($schemaAttribute->constraints());
        $this->assertSame(true, $schemaAttribute->isRequired());
        $this->assertSame(1, $schemaAttribute->min());
        $this->assertSame(8, $schemaAttribute->minLength());
        $this->assertSame(3, $schemaAttribute->minCheck());
        $this->assertSame(100, $schemaAttribute->max());
        $this->assertSame(10, $schemaAttribute->maxLength());
        $this->assertSame(4, $schemaAttribute->maxCheck());
        $this->assertSame('#bar', $schemaAttribute->equalTo());
        $this->assertSame('number', $schemaAttribute->type());
    }

    public function getRequiredConstraint(): array
    {
        return [
            ['', false],
            ['required', true],
            ['required:true', true],
            ['required:false', false],
            [['required'], true],
            [['required' => true], true],
            [['required' => false], false],
        ];
    }

    public function getMinLengthConstraint(): array
    {
        return [
            ['', null],
            ['minlength:8', 8],
            [['minlength' => 8], 8],
            [['minlength' => '8'], 8],
        ];
    }

    public function getMinConstraint(): array
    {
        return [
            ['', null],
            ['min:8', 8],
            [['min' => 8], 8],
            [['min' => '8'], 8],
        ];
    }

    public function getMaxLengthConstraint(): array
    {
        return [
            ['', null],
            ['maxlength:10', 10],
            [['maxlength' => 10], 10],
            [['maxlength' => '10'], 10],
        ];
    }

    public function getMaxConstraint(): array
    {
        return [
            ['', null],
            ['max:100', 100],
            [['max' => 100], 100],
            [['max' => '100'], 100],
        ];
    }

    public function getMinCheckConstraint(): array
    {
        return [
            ['', null],
            ['mincheck:3', 3],
            [['mincheck' => 3], 3],
            [['mincheck' => '3'], 3],
        ];
    }

    public function getMaxCheckConstraint(): array
    {
        return [
            ['', null],
            ['maxcheck:4', 4],
            [['maxcheck' => 4], 4],
            [['maxcheck' => '4'], 4],
        ];
    }

    public function getCheckConstraint(): array
    {
        return [
            ['', null, null],
            ['check:3,4', 3, 4],
            [['check' => [
                'min' => 5,
                'max' => 6,
            ]], 5, 6],
        ];
    }

    public function getLengthConstraint(): array
    {
        return [
            ['', null, null],
            ['length:30,40', 30, 40],
            [['length' => [
                'min' => 5,
                'max' => 60,
            ]], 5, 60],
        ];
    }

    public function getEqualToConstraint(): array
    {
        return [
            ['', null],
            ['equalto:#foo', '#foo'],
            [['equalto' => '.foo'], '.foo'],
        ];
    }

    public function getTypeConstraint(): array
    {
        return [
            ['', null],
            ['type:number', 'number'],
            [['type' => 'text'], 'text'],
        ];
    }

    public function getFkConstraint(): array
    {
        return [
            ['fk:table', 'table', 'id', 'name'],
            ['fk:table2,username', 'table2', 'id', 'username'],
            ['fk:table3,description,uuid', 'table3', 'uuid', 'description'],
            [['fk' => 'table5'], 'table5', 'id', 'name'],
            [['fk' => 'table6,username'], 'table6', 'id', 'username'],
            [['fk' => 'table7,description,uuid'], 'table7', 'uuid', 'description'],
        ];
    }
}
