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
    /**
     * @param null|array|string $constraints
     */
    public function __construct(string $name, string $dataType, $constraints = null);

    public function name(): string;

    public function dataType(): string;

    /**
     * @return array<string, mixed>
     */
    public function constraints(): array;

    /**
     * @return array<string, mixed>
     */
    public function properties(): array;

    public function type(): ?string;

    public function isRequired(): bool;

    public function minLength(): ?int;

    public function maxLength(): ?int;

    public function minCheck(): ?int;

    public function maxCheck(): ?int;

    public function min(): ?int;

    public function max(): ?int;

    public function equalTo(): ?string;

    public function isPk(): bool;

    public function isAi(): bool;

    public function isFk(): bool;

    public function fkTable(): ?string;

    public function fkId(): ?string;

    public function fkName(): ?string;
}
