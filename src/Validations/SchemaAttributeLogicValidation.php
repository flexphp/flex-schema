<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validations;

use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\Validators\Logics\ActionLogicValidator;
use FlexPHP\Schema\Validators\Logics\AiLogicValidator;
use FlexPHP\Schema\Validators\Logics\DefaultLogicValidator;
use FlexPHP\Schema\Validators\Logics\FcharsLogicValidator;
use FlexPHP\Schema\Validators\Logics\FcheckLogicValidator;
use FlexPHP\Schema\Validators\Logics\FormatLogicValidator;
use FlexPHP\Schema\Validators\Logics\PkLogicValidator;
use FlexPHP\Schema\Validators\Logics\TypeLogicValidator;
use Throwable;

class SchemaAttributeLogicValidation implements ValidationInterface
{
    private const VALIDATORS = [
        PkLogicValidator::class,
        AiLogicValidator::class,
        TypeLogicValidator::class,
        FormatLogicValidator::class,
        FcharsLogicValidator::class,
        FcheckLogicValidator::class,
        ActionLogicValidator::class,
        DefaultLogicValidator::class,
    ];

    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if (empty($this->property->constraints())) {
            return;
        }

        $name = 'Logic: [' . $this->property->name() . '] ';

        try {
            \array_map(function ($validator): void {
                (new $validator($this->property))->validate();
            }, self::VALIDATORS);
        } catch (Throwable $throwable) {
            throw new InvalidSchemaAttributeException($name . $throwable->getMessage());
        }
    }
}
