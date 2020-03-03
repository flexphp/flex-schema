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
use Symfony\Component\Yaml\Yaml;

class SchemaTest extends TestCase
{
    public function testItSchemaFromArrayEmptyThrowException(): void
    {
        $this->expectException(\ArgumentCountError::class);

        $schema = new Schema();
        $schema->fromArray();
    }

    public function testItSchemaFromArrayInvalidArgumentThrowException(): void
    {
        $this->expectException(\TypeError::class);

        $schema = new Schema();
        $schema->fromArray(null);
    }

    public function testItSchemaFromArrayEmptyNotThrowException(): void
    {
        $schema = new Schema();
        $schema->fromArray([]);

        $this->assertTrue(true);
    }

    public function testItSchemaFromArrayEmptyValidateThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);

        $schema = new Schema();
        $schema->fromArray([]);
        $schema->load();
    }

    /**
     * @dataProvider getNameInvalid
     */
    public function testItSchemaFromArrayNameInvalidThrowException($name): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage('name is');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array[$name] = $array['table'];
        unset($array['table']);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();
    }

    public function testItSchemaFromArrayWithoutTitleThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':title');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::TITLE]);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();
    }

    /**
     * @dataProvider getTitleInvalid
     */
    public function testItSchemaFromArrayTitleInvalidThrowException($title): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':title');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array['table'][Keyword::TITLE] = $title;

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();
    }

    public function testItSchemaFromArrayWithoutTableAttributesThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':attributes are');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::ATTRIBUTES]);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();
    }

    /**
     * @dataProvider getAttributesInvalid
     */
    public function testItSchemaFromArrayAttributesInvalidThrowException($attributes): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaException::class);
        $this->expectExceptionMessage(':attributes are');

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $array['table'][Keyword::ATTRIBUTES] = $attributes;

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();
    }

    public function testItSchemaFromArrayWithTableAttributesInvalidThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);

        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        unset($array['table'][Keyword::ATTRIBUTES]['column3'][Keyword::DATATYPE]);

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();
    }

    public function testItSchemaFromArrayOk(): void
    {
        $array = (new Yaml())->parseFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));

        $schema = new Schema();
        $schema->fromArray($array);
        $schema->load();

        $this->assertEquals('table', $schema->name());
        $this->assertEquals('Table Name', $schema->title());
        $this->assertIsArray($schema->attributes());
    }

    public function testItSchemaFromFileEmptyThrowException(): void
    {
        $this->expectException(\ArgumentCountError::class);

        $schema = new Schema();
        $schema->fromFile();
    }

    public function testItSchemaFromFileInvalidArgumentThrowException(): void
    {
        $this->expectException(\TypeError::class);

        $schema = new Schema();
        $schema->fromFile(null);
    }

    public function testItSchemaFromFileNotExistsThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidFileSchemaException::class);

        $schema = new Schema();
        $schema->fromFile('/path/error');
    }

    public function testItSchemaFromFileFormatErrorThrowException(): void
    {
        $this->expectException(\FlexPHP\Schema\Exception\InvalidSchemaAttributeException::class);

        $schema = new Schema();
        $schema->fromFile(\sprintf('%s/../Mocks/yaml/error.yaml', __DIR__));
        $schema->load();
    }

    public function testItSchemaFromFileOk(): void
    {
        $schema = new Schema();
        $schema->fromFile(\sprintf('%s/../Mocks/yaml/table.yaml', __DIR__));
        $schema->load();

        $this->assertEquals('table', $schema->name());
        $this->assertEquals('Table Name', $schema->title());
        $this->assertIsArray($schema->attributes());
    }

    public function getNameInvalid(): array
    {
        return [
            [null],
            [''],
            [' '],
        ];
    }

    public function getTitleInvalid(): array
    {
        return [
            [null],
            [''],
            [' '],
        ];
    }

    public function getAttributesInvalid(): array
    {
        return [
            [null],
            [[]],
        ];
    }
}
