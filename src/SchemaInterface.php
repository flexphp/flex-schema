<?php

namespace FlexPHP\Schema;

interface SchemaInterface
{
    public function fromArray(array $schema): void;

    public function fromFile(string $filename): void;

    public function validate(): void;

    public function table(): string;

    public function title(): string;

    public function attributes(): array;
}
