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
     * Schema in array format
     *
     * @param array<array> $schema
     */
    public function fromArray(array $schema): void;

    /**
     * Filename from schema file
     */
    public function fromFile(string $filename): void;

    /**
     * Instance vars from input loaded
     */
    public function load(): void;

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
     * @return array<array>
     */
    public function attributes(): array;
}
