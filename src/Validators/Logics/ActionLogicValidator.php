<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Validators\Logics;

use FlexPHP\Schema\Constants\Action;
use FlexPHP\Schema\Exception\InvalidSchemaAttributeException;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\Validations\ValidationInterface;

class ActionLogicValidator implements ValidationInterface
{
    private const ACTIONS = [
        Action::ALL,
        Action::INDEX,
        Action::CREATE,
        Action::READ,
        Action::UPDATE,
        Action::DELETE,
    ];

    private SchemaAttributeInterface $property;

    public function __construct(SchemaAttributeInterface $property)
    {
        $this->property = $property;
    }

    public function validate(): void
    {
        if ($this->property->usedIn(Action::ALL) && \count($this->property->show()) > 1) {
            throw new InvalidSchemaAttributeException('Show constraint miss-configuration: ALL option is exclusive');
        }

        if ($this->property->usedIn(Action::ALL) && \count($this->property->hide()) > 1) {
            throw new InvalidSchemaAttributeException('Hide constraint miss-configuration: ALL option is exclusive');
        }

        \array_map(function (string $action): void {
            if (\in_array($action, $this->property->show()) && \in_array($action, $this->property->hide())) {
                throw new InvalidSchemaAttributeException(
                    'Show/Hide constraint miss-configuration: (' . $action . ') option is present in both',
                );
            }
        }, self::ACTIONS);
    }
}
