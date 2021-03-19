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

interface Operator
{
    public const EQUALS = 'eq';

    public const NOTEQUALS = 'ne';

    public const GREATER = 'gt';

    public const GREATEROREQUALS = 'ge';

    public const LESS = 'lt';

    public const LESSOREQUALS = 'le';

    public const NUL = 'nl';

    public const NOTNUL = 'nn';

    public const IN = 'in';

    public const NOTIN = 'ni';

    public const STARTS = 'ss';

    public const ENDS = 'se';

    public const CONTAINS = 'sc';

    public const EXPLODE = 'sx';

    public const BETWEEN = 'bw';
}
