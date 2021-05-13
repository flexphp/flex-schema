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

interface Action
{
    public const ALL = 'a';

    public const INDEX = 'i';

    public const CREATE = 'c';

    public const READ = 'r';

    public const UPDATE = 'u';

    public const DELETE = 'd';

    public const FILTER = 'f';
}
