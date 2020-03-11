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

interface SchemaAttributeInterface
{
    public function validate(): void;

    public function name(): string;

    public function setName(string $name): void;

    public function dataType(): string;

    public function setDataType(string $dataType): void;

    /**
     * @return array<array>
     */
    public function constraints(): array;

    /**
     * @param array<array> $constraints
     */
    public function setConstraints(array $constraints): void;

    public function type(): string;

    public function setType(string $type): void;
}
