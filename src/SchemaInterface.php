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

interface SchemaInterface
{
    /**
     * Schema load from array format
     *
     * @param array<array> $schema
     */
    public static function fromArray(array $schema): self;

    /**
     * Filename from schema file
     */
    public static function fromFile(string $schemafile): self;

    /**
     * @param array<SchemaAttributeInterface> $attributes
     */
    public function __construct(string $name, string $title, array $attributes);

    /**
     * Get internal name from schema
     */
    public function name(): string;

    /**
     * Get name used to show it a user
     */
    public function title(): string;

    /**
     * Get attributes's schema
     *
     * @return array<SchemaAttributeInterface>
     */
    public function attributes(): array;
}
