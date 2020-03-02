<?php

namespace FlexPHP\Schema\Tests\Domain\Validations;

use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Exception\AttributeValidationException;
use FlexPHP\Schema\Validations\AttributeValidation;
use FlexPHP\Schema\Validators\PropertyDataTypeValidator;
use FlexPHP\Schema\Validators\PropertyTypeValidator;
use FlexPHP\Schema\Tests\TestCase;

class AttributeValidationTest extends TestCase
{
    public function testItPropertyUnknowThrownException(): void
    {
        $this->expectException(AttributeValidationException::class);
        $this->expectExceptionMessage('unknow');

        $validation = new AttributeValidation([
            'UnknowProperty' => 'Test',
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyNameNotValid
     */
    public function testItPropertyNameNotValidThrownException($name): void
    {
        $this->expectException(AttributeValidationException::class);
        $this->expectExceptionMessage('Name:');

        $validation = new AttributeValidation([
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
        $validation = new AttributeValidation([
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
        $this->expectException(AttributeValidationException::class);
        $this->expectExceptionMessage('DataType:');

        $validation = new AttributeValidation([
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
        $validation = new AttributeValidation([
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
        $this->expectException(AttributeValidationException::class);
        $this->expectExceptionMessage('Type:');

        $validation = new AttributeValidation([
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
        $validation = new AttributeValidation([
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
        $this->expectException(AttributeValidationException::class);
        $this->expectExceptionMessage('Constraints:');

        $validation = new AttributeValidation([
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
        $validation = new AttributeValidation([
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
            ['#Name'],
            ['1Name'],
            ['Name$'],
            [str_repeat('N', 65)],
            [''],
        ];
    }

    public function propertyNameValid(): array
    {
        return [
            ['Name'],
            ['N123'],
            ['Name_Test'],
            ['name_test'],
            ['_name'],
            [str_repeat('N', 64)],
            ['N'],
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
            [null],
            [[]],
            [1],
        ];
    }

    public function propertyDataTypeValid(): array
    {
        return array_map(function ($dataType) {
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
        return array_map(function ($dataType) {
            return [$dataType];
        }, PropertyTypeValidator::ALLOWED_TYPES);
    }

    public function propertyConstraintsNotValid(): array
    {
        return [
            ['_REQUIRED'],
            ['REQUIRED'],
            ['Required'],
            [1],
            [['minlength' => null]],
            [['maxlength' => []]],
            [['mincheck' => -1]],
            [['maxcheck' => 0]],
            [['min' => '']],
            [['max' => 'null']],
            [['equalto' => null]],
            [['type' => 'unknow']],
            [['check' => [
                'min' => rand(5, 10),
            ]]],
            [['check' => [
                'min' => rand(5, 10),
                'max' => rand(0, 4),
            ]]],
            [['length' => [
                'max' => rand(0, 5),
            ]]],
            [['length' => [
                'min' => rand(5, 10),
                'max' => rand(0, 5),
            ]]],
            [['length' => [
                'min' => rand(5, 10),
            ], 'type' => 'text']],
        ];
    }

    public function propertyConstraintsValid(): array
    {
        return [
            [null],
            [''],
            [[]],
            ['required'],
            ['required|min:8'], // Using |
            ["['required']"], // Array syntax
            ["['required','min'=>8]"], // Array syntax multiple
            ['["required"]'], // JSON simple
            ['{"required":true}'], // JSON complex
            ['{"required":true,"min":8}'], // JSON complex multiple
            [['required']],
            [['required' => true]],
            [['required' => false]],
            [['minlength' => 0]],
            [['minlength' => rand(0, 9)]],
            [['maxlength' => rand(1, 9)]],
            [['mincheck' => 0]],
            [['mincheck' => rand(1, 9)]],
            [['maxcheck' => rand(1, 9)]],
            [['min' => 0]],
            [['min' => rand(1, 9)]],
            [['max' => rand(1, 9)]],
            [['equalto' => 'foo']],
            [['type' => 'text']],
            [['check' => [
                'min' => rand(0, 4),
                'max' => rand(5, 10),
            ]]],
            [['length' => [
                'min' => rand(0, 4),
                'max' => rand(5, 10),
            ]]],
        ];
    }
}
