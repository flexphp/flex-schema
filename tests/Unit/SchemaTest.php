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
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;
use FlexPHP\Schema\SchemaAttributeInterface;
use Symfony\Component\Yaml\Yaml;

class SchemaTest extends TestCase
{
    /**
     * @dataProvider getNameInvalid
     *
     * @param mixed $name
     */
    public function testItSchemaNameInvalidThrowException($name): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage('name is');

        new Schema($name, 'title', []);
    }

    /**
     * @dataProvider getTitleInvalid
     *
     * @param mixed $title
     */
    public function testItSchemaTitleInvalidThrowException($title): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':title');

        new Schema('name', $title, []);
    }

    public function testItSchemaAttributesEmptyOk(): void
    {
        new Schema('name', 'title', []);

        $this->assertTrue(true);
    }

    public function testItSchemaSetOk(): void
    {
        $name = 'name';
        $title = 'title';
        $attributes = [
            [
                Keyword::NAME => 'foo',
                Keyword::DATATYPE => 'string',
                Keyword::CONSTRAINTS => 'required:true',
            ],
            [
                Keyword::NAME => 'bar',
                Keyword::DATATYPE => 'integer',
                Keyword::CONSTRAINTS => 'required:false|min:8|max:10',
            ],
        ];

        $schema = new Schema($name, $title, $attributes);

        $this->assertEquals($name, $schema->name());
        $this->assertEquals($title, $schema->title());
        $this->assertIsArray($schema->attributes());
        $this->assertSame(2, \count($schema->attributes()));
        $this->assertEquals('id', $schema->pkName());

        foreach ($schema->attributes() as $attribute) {
            $this->assertInstanceOf(SchemaAttributeInterface::class, $attribute);
        }

        $attribute = $schema->attributes()[1];

        $this->assertSame(false, $attribute->isRequired());
        $this->assertSame(8, $attribute->min());
        $this->assertSame(10, $attribute->max());
    }

    /**
     * @dataProvider getNameInvalid
     *
     * @param mixed $name
     */
    public function testItSchemaFromArrayNameInvalidThrowException($name): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage('name is');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array[$name] = $array['table'];
        unset($array['table']);

        Schema::fromArray($array);
    }

    public function testItSchemaFromArrayWithoutTitleThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':title');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::TITLE]);

        Schema::fromArray($array);
    }

    /**
     * @dataProvider getTitleInvalid
     *
     * @param mixed $title
     */
    public function testItSchemaFromArrayTitleInvalidThrowException($title): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':title');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array['table'][Keyword::TITLE] = $title;

        Schema::fromArray($array);
    }

    public function testItSchemaFromArrayWithoutTableAttributesOk(): void
    {
        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::ATTRIBUTES]);

        Schema::fromArray($array);

        $this->assertTrue(true);
    }

    public function testItSchemaFromArrayAttributesEmptyOk(): void
    {
        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array['table'][Keyword::ATTRIBUTES] = [];

        Schema::fromArray($array);

        $this->assertTrue(true);
    }

    public function testItSchemaFromArrayAttributesWithInterfaceOk(): void
    {
        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array['table'][Keyword::ATTRIBUTES] = [new SchemaAttribute('foo', 'integer')];

        Schema::fromArray($array);

        $this->assertTrue(true);
    }

    public function testItSchemaFromArrayWithTableAttributesInvalidThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::ATTRIBUTES]['column3'][Keyword::DATATYPE]);

        Schema::fromArray($array);
    }

    public function testItSchemaFromArrayOk(): void
    {
        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));

        $schema = Schema::fromArray($array);

        $this->assertEquals('table', $schema->name());
        $this->assertEquals('fas fa-icon', $schema->icon());
        $this->assertEquals('Table Name', $schema->title());
        $this->assertEquals('PrimaryColumn', $schema->pkName());
        $this->assertEquals('int', $schema->pkTypeHint());
        $this->assertIsArray($schema->attributes());
        $this->assertIsArray($schema->fkRelations());
        $this->assertEquals('en', $schema->language());

        foreach ($schema->attributes() as $attribute) {
            $this->assertInstanceOf(SchemaAttributeInterface::class, $attribute);
        }
    }

    public function testItSchemaFromFileNotExistsThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidFileSchemaException::class);

        Schema::fromFile('/path/error');
    }

    public function testItSchemaFromFileFormatErrorThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);

        Schema::fromFile(\sprintf('%s/../Mocks/yaml/error.yaml', __DIR__));
    }

    public function testItSchemaFromFileOk(): void
    {
        $schema = Schema::fromFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));

        $this->assertEquals('table', $schema->name());
        $this->assertEquals('fas fa-icon', $schema->icon());
        $this->assertEquals('Table Name', $schema->title());
        $this->assertEquals('PrimaryColumn', $schema->pkName());
        $this->assertEquals('int', $schema->pkTypeHint());
        $this->assertIsArray($schema->attributes());
        $this->assertIsArray($schema->fkRelations());
        $this->assertEquals('en', $schema->language());

        foreach ($schema->attributes() as $attribute) {
            $this->assertInstanceOf(SchemaAttributeInterface::class, $attribute);
        }
    }

    public function getNameInvalid(): array
    {
        return [
            [''],
            [' '],
        ];
    }

    public function getTitleInvalid(): array
    {
        return [
            [''],
            [' '],
        ];
    }
}
