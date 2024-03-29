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

interface Rule
{
    public const REQUIRED = 'required';

    public const MINLENGTH = 'minlength';

    public const MAXLENGTH = 'maxlength';

    public const LENGTH = 'length';

    public const MINCHECK = 'mincheck';

    public const MAXCHECK = 'maxcheck';

    public const CHECK = 'check';

    public const MIN = 'min';

    public const MAX = 'max';

    public const EQUALTO = 'equalto';

    public const TYPE = 'type';

    public const PRIMARYKEY = 'pk';

    public const FOREIGNKEY = 'fk';

    public const AUTOINCREMENT = 'ai';

    public const CREATEDAT = 'ca';

    public const UPDATEDAT = 'ua';

    public const CREATEDBY = 'cb';

    public const UPDATEDBY = 'ub';

    public const FILTER = 'filter';

    public const FORMAT = 'format';

    public const TRIM = 'trim';

    public const FCHARS = 'fchars';

    public const FKCHECK = 'fkcheck';

    public const LINK = 'link';

    public const SHOW = 'show';

    public const HIDE = 'hide';

    public const DEFAULT = 'default';

    public const PATTERN = 'pattern';
}
