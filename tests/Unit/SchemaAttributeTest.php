<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Tests\Unit;

use FlexPHP\Schema\SchemaAttribute;
use FlexPHP\Schema\Tests\TestCase;

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

    /**
     * @dataProvider getConstraintLogicError
     */
    public function testItSchemaAttributeConstraintLogicError(string $dataType, string $constraints): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('Logic:');

        new SchemaAttribute('foo', $dataType, $constraints);
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
        $dataType = 'string';

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
     * @dataProvider getPkConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributePkConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'string';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isPk());
    }

    /**
     * @dataProvider getAiConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeAiConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isAi());
    }

    /**
     * @dataProvider getCaConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeCaConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'datetime';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isCa());
        $this->assertSame($expected, $schemaAttribute->isBlame());
        $this->assertSame($expected, $schemaAttribute->isBlameAt());
        $this->assertSame(false, $schemaAttribute->isBlameBy());
    }

    /**
     * @dataProvider getUaConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeUaConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'datetime';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isUa());
        $this->assertSame($expected, $schemaAttribute->isBlame());
        $this->assertSame($expected, $schemaAttribute->isBlameAt());
        $this->assertSame(false, $schemaAttribute->isBlameBy());
    }

    /**
     * @dataProvider getCbConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeCbConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isCb());
        $this->assertSame($expected, $schemaAttribute->isBlame());
        $this->assertSame(false, $schemaAttribute->isBlameAt());
        $this->assertSame($expected, $schemaAttribute->isBlameBy());
    }

    /**
     * @dataProvider getUbConstraint
     *
     * @param mixed $constraint
     * @param mixed $expected
     */
    public function testItSchemaAttributeUbConstraints($constraint, $expected): void
    {
        $name = 'foo';
        $dataType = 'integer';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraint);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertSame($expected, $schemaAttribute->isUb());
        $this->assertSame($expected, $schemaAttribute->isBlame());
        $this->assertSame(false, $schemaAttribute->isBlameAt());
        $this->assertSame($expected, $schemaAttribute->isBlameBy());
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
        $this->assertEquals('int', $schemaAttribute->typeHint());
        $this->assertTrue($schemaAttribute->isFk());
        $this->assertSame($fkTable, $schemaAttribute->fkTable());
        $this->assertSame($fkId, $schemaAttribute->fkId());
        $this->assertSame($fkName, $schemaAttribute->fkName());
    }

    public function testItSchemaAttributeConstraintsAsString(): void
    {
        $name = 'foo';
        $dataType = 'string';
        $constraints = 'required|minlength:8|maxlength:10|mincheck:3|maxcheck:4|equalto:#bar|type:number';

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertEquals('string', $schemaAttribute->typeHint());
        $this->assertIsArray($schemaAttribute->constraints());
        $this->assertSame(true, $schemaAttribute->isRequired());
        $this->assertNull($schemaAttribute->min());
        $this->assertSame(8, $schemaAttribute->minLength());
        $this->assertSame(3, $schemaAttribute->minCheck());
        $this->assertNull($schemaAttribute->max());
        $this->assertSame(10, $schemaAttribute->maxLength());
        $this->assertSame(4, $schemaAttribute->maxCheck());
        $this->assertSame('#bar', $schemaAttribute->equalTo());
        $this->assertSame('number', $schemaAttribute->type());
        $this->assertFalse($schemaAttribute->isPk());
        $this->assertFalse($schemaAttribute->isAi());
        $this->assertFalse($schemaAttribute->isFk());
        $this->assertNull($schemaAttribute->fkTable());
        $this->assertNull($schemaAttribute->fkId());
        $this->assertNull($schemaAttribute->fkName());
    }

    public function testItSchemaAttributeConstraintsAsArray(): void
    {
        $name = 'foo';
        $dataType = 'integer';
        $constraints = [
            'required',
            'equalto' => '#id',
            'type' => 'text',
            'pk' => true,
            'ai' => true,
        ];

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertEquals('int', $schemaAttribute->typeHint());
        $this->assertIsArray($schemaAttribute->constraints());
        $this->assertSame(true, $schemaAttribute->isRequired());
        $this->assertNull($schemaAttribute->min());
        $this->assertNull($schemaAttribute->minLength());
        $this->assertNull($schemaAttribute->minCheck());
        $this->assertNull($schemaAttribute->max());
        $this->assertNull($schemaAttribute->maxLength());
        $this->assertNull($schemaAttribute->maxCheck());
        $this->assertSame('#id', $schemaAttribute->equalTo());
        $this->assertSame('text', $schemaAttribute->type());
        $this->assertTrue($schemaAttribute->isPk());
        $this->assertTrue($schemaAttribute->isAi());
        $this->assertFalse($schemaAttribute->isFk());
    }

    public function testItSchemaAttributeConstraintsAsArrayCast(): void
    {
        $name = 'foo';
        $dataType = 'datetime';
        $constraints = [
            'required',
            'equalto' => '#bar',
            'type' => 'number',
        ];

        $schemaAttribute = new SchemaAttribute($name, $dataType, $constraints);

        $this->assertEquals($name, $schemaAttribute->name());
        $this->assertEquals($dataType, $schemaAttribute->dataType());
        $this->assertEquals('\DateTime', $schemaAttribute->typeHint());
        $this->assertIsArray($schemaAttribute->constraints());
        $this->assertSame(true, $schemaAttribute->isRequired());
        $this->assertNull($schemaAttribute->min());
        $this->assertNull($schemaAttribute->minLength());
        $this->assertNull($schemaAttribute->minCheck());
        $this->assertNull($schemaAttribute->max());
        $this->assertNull($schemaAttribute->maxLength());
        $this->assertNull($schemaAttribute->maxCheck());
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

    public function getPkConstraint(): array
    {
        return [
            ['', false],
            ['required|pk', true],
            ['required|pk:true', true],
            ['required|pk:false', false],
            [['required', 'pk'], true],
            [['required', 'pk' => true], true],
            [['required', 'pk' => false], false],
        ];
    }

    public function getAiConstraint(): array
    {
        return [
            ['', false],
            ['required|pk|ai', true],
            ['required|pk|ai:true', true],
            // ['required|pk|ai:false', false],
            [['required', 'pk', 'ai'], true],
            [['required', 'pk', 'ai' => true], true],
            // [['required', 'pk', 'ai' => false], false],
        ];
    }

    public function getCaConstraint(): array
    {
        return [
            ['', false],
            ['ca', true],
            ['ca:true', true],
            ['ca:false', false],
            [['ca'], true],
            [['ca' => true], true],
            [['ca' => false], false],
        ];
    }

    public function getUaConstraint(): array
    {
        return [
            ['', false],
            ['ua', true],
            ['ua:true', true],
            ['ua:false', false],
            [['ua'], true],
            [['ua' => true], true],
            [['ua' => false], false],
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

    public function getCbConstraint(): array
    {
        return [
            ['', false],
            ['cb', true],
            ['cb:true', true],
            ['cb:false', false],
            [['cb'], true],
            [['cb' => true], true],
            [['cb' => false], false],
        ];
    }

    public function getUbConstraint(): array
    {
        return [
            ['', false],
            ['ub', true],
            ['ub:true', true],
            ['ub:false', false],
            [['ub'], true],
            [['ub' => true], true],
            [['ub' => false], false],
        ];
    }

    public function getConstraintLogicError(): array
    {
        return [
            ['integer', 'pk|ai|required|min:10'],
            ['integer', 'pk|ai|required|fk:table'],
            ['integer', 'pk|required'],
            ['integer', 'ai'],
            ['integer', 'ai|pk'],
            ['string', 'ai|pk|required'],
            ['string', 'ca'],
            ['integer', 'ua'],
            ['datetime', 'ca|ua'],
            ['integer', 'fk:table|ai|pk|required'],
            ['integer', 'required:false|ai|pk'],
            ['integer', 'required:false|pk'],
            ['smallint', 'maxlength:100'],
            ['integer', 'maxlength:100'],
            ['bigint', 'maxlength:100'],
            ['double', 'maxlength:100'],
            ['float', 'maxlength:100'],
            ['smallint', 'minlength:10'],
            ['integer', 'minlength:10'],
            ['bigint', 'minlength:10'],
            ['double', 'minlength:10'],
            ['float', 'minlength:10'],
            ['string', 'min:100'],
            ['text', 'max:10'],
            ['guid', 'min:10'],
            ['binary', 'max:10'],
            ['blob', 'maxlength:1'],
            ['datetimetz', 'maxlength:1'],
            ['time', 'mincheck:1'],
            ['datetime', 'maxcheck:1'],
            ['datetime', 'cb'],
            ['bool', 'ub'],
            ['binary', 'cb'],
            ['blob', 'ub'],
            ['integer', 'cb|ub'],
        ];
    }
}
