<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Tests\Validations;

use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\Tests\TestCase;
use FlexPHP\Schema\Validations\SchemaAttributeValidation;
use FlexPHP\Schema\Validators\PropertyDataTypeValidator;
use FlexPHP\Schema\Validators\PropertyTypeValidator;

class SchemaAttributeValidationTest extends TestCase
{
    public function testItPropertyRequireThrownException(): void
    {
        $this->expectException(InvalidSchemaAttributeException::class);

        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'Test',
        ]);

        $validation->validate();
    }

    public function testItPropertyUnknowThrownException(): void
    {
        $this->expectException(InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('unknow');

        $validation = new SchemaAttributeValidation([
            'UnknowProperty' => 'Test',
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyNameNotValid
     */
    public function testItPropertyNameNotValidThrownException($name): void
    {
        $this->expectException(InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('Name:');

        $validation = new SchemaAttributeValidation([
            Keyword::NAME => $name,
            Keyword::DATATYPE => 'string',
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyNameValid
     */
    public function testItPropertyNameOk($name): void
    {
        $validation = new SchemaAttributeValidation([
            Keyword::NAME => $name,
            Keyword::DATATYPE => 'string',
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    /**
     * @dataProvider propertyDataTypeNotValid
     */
    public function testItPropertyDataTypeNotValidThrownException($dataType): void
    {
        $this->expectException(InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('DataType:');

        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'foo',
            Keyword::DATATYPE => $dataType,
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyDataTypeValid
     */
    public function testItPropertyDataTypeOk($dataType): void
    {
        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'foo',
            Keyword::DATATYPE => $dataType,
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    /**
     * @dataProvider propertyDataTypeNotValid
     */
    public function testItPropertyTypeNotValidThrownException($type): void
    {
        $this->expectException(InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('Type:');

        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'foo',
            Keyword::DATATYPE => 'string',
            Keyword::TYPE => $type,
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyTypeValid
     */
    public function testItPropertyTypeOk($type): void
    {
        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'foo',
            Keyword::DATATYPE => 'string',
            Keyword::TYPE => $type,
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    /**
     * @dataProvider propertyConstraintsNotValid
     */
    public function testItPropertyConstraintsNotValidThrownException($constraints): void
    {
        $this->expectException(InvalidSchemaAttributeException::class);
        $this->expectExceptionMessage('Constraints:');

        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'foo',
            Keyword::DATATYPE => 'string',
            Keyword::CONSTRAINTS => $constraints,
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyConstraintsValid
     */
    public function testItPropertyConstraintsOk($constraints): void
    {
        $validation = new SchemaAttributeValidation([
            Keyword::NAME => 'foo',
            Keyword::DATATYPE => 'string',
            Keyword::CONSTRAINTS => $constraints,
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    public function propertyNameNotValid(): array
    {
        return [
            [''],
            [' '],
            ['_'],
            ['_name'],
            ['name_'],
            ['1Name'],
            ['$Name'],
            ['Na$me'],
            ['Name$'],
            [\str_repeat('N', 65)],
        ];
    }

    public function propertyNameValid(): array
    {
        return [
            ['n'],
            ['N'],
            ['Name'],
            ['N123'],
            ['Name_Test'],
            ['name_test'],
            [\str_repeat('N', 64)],
        ];
    }

    public function propertyDataTypeNotValid(): array
    {
        return [
            ['unknow'],
            ['bool'],
            ['barchar'],
            ['interger'],
            ['int'],
        ];
    }

    public function propertyDataTypeValid(): array
    {
        return \array_map(function ($dataType) {
            return [$dataType];
        }, PropertyDataTypeValidator::ALLOWED_DATATYPES);
    }

    public function propertyTypeNotValid(): array
    {
        return [
            ['unknow'],
            ['textt'],
            ['text area'],
            ['int'],
            [null],
            [[]],
            [1],
        ];
    }

    public function propertyTypeValid(): array
    {
        return \array_map(function ($dataType) {
            return [$dataType];
        }, PropertyTypeValidator::ALLOWED_TYPES);
    }

    public function propertyConstraintsNotValid(): array
    {
        return [
            [['']],
            [['required']],
            [['_REQUIRED']],
            [['REQUIRED']],
            [['Required']],
            [['required' => null]],
            [['required' => '']],
            [[1]],
            [['minlength' => null]],
            [['maxlength' => []]],
            [['mincheck' => -1]],
            [['maxcheck' => 0]],
            [['min' => '']],
            [['max' => 'null']],
            [['equalto' => null]],
            [['type' => 'unknow']],
            [['check' => [
                'min' => \rand(5, 10),
            ]]],
            [['check' => [
                'min' => \rand(5, 10),
                'max' => \rand(0, 4),
            ]]],
            [['length' => [
                'max' => \rand(0, 5),
            ]]],
            [['length' => [
                'min' => null,
                'max' => \rand(0, 5),
            ]]],
            [['length' => [
                'min' => \rand(5, 10),
                'max' => \rand(0, 4),
            ]]],
            [['length' => [
                'min' => \rand(0, 5),
                'max' => null,
            ]]],
            [['length' => [
                'min' => \rand(5, 10),
            ]]],
        ];
    }

    public function propertyConstraintsValid(): array
    {
        return [
            [[]],
            [['required' => true]],
            [['required' => false]],
            [['required' => 'true']],
            [['required' => 'false']],
            [['minlength' => 0]],
            [['minlength' => \rand(0, 9)]],
            [['maxlength' => \rand(1, 9)]],
            [['mincheck' => 0]],
            [['mincheck' => \rand(1, 9)]],
            [['maxcheck' => \rand(1, 9)]],
            [['min' => 0]],
            [['min' => \rand(1, 9)]],
            [['max' => \rand(1, 9)]],
            [['equalto' => 'foo']],
            [['type' => 'text']],
            [['check' => [
                'min' => \rand(0, 4),
                'max' => \rand(5, 10),
            ]]],
            [['length' => [
                'min' => \rand(0, 4),
                'max' => \rand(5, 10),
            ]]],
        ];
    }
}
