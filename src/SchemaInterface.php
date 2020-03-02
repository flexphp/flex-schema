<?php

namespace FlexPHP\Schema;

interface SchemaInterface
{
    /**
     * Schema in array format
     *
     * @param array<array> $schema
     * @return void
     */
    public function fromArray(array $schema): void;

    /**
     * Filename from schema file
     *
     * @param string $filename
     * @return void
     */
    public function fromFile(string $filename): void;

    /**
     * Instance vars from input loaded
     *
     * @return void
     */
    public function validate(): void;

    /**
     * Get internal name from schema
     *
     * @return string
     */
    public function name(): string;

    /**
     * Get name used to show it a user
     *
     * @return string
     */
    public function title(): string;

    /**
     * Get attributes's schema
     *
     * @return array<array>
     */
    public function attributes(): array;
}
