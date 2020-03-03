<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Schema\Constants;

interface Keyword
{
    /**
     * Schema properties
     */
    public const TITLE = 'Title';

    public const ATTRIBUTES = 'Attributes';

    /**
     * Attribute properties
     */
    public const NAME = 'Name';

    public const DATATYPE = 'DataType';

    public const TYPE = 'Type';

    public const CONSTRAINTS = 'Constraints';
}
