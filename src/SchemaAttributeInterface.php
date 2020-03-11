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
     * @param mixed $constraints
     */
    public function setConstraints($constraints): void;

    public function type(): string;

    public function setType(string $type): void;

    public function isRequired(): bool;

    public function minLength(): ?int;

    public function maxLength(): ?int;

    public function minCheck(): ?int;

    public function maxCheck(): ?int;

    public function min(): ?int;

    public function max(): ?int;

    public function equalTo(): ?string;
}
