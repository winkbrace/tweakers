<?php declare(strict_types=1);

namespace Tweakers\Common;

/**
 * This enum represents the ORDER BY direction
 *
 * @method bool isAsc()
 * @method bool isDesc()
 *
 * @method static OrderDirection ASC()
 * @method static OrderDirection DESC()
 */
class OrderDirection extends Enum
{
    const ASC  = 'asc';
    const DESC  = 'desc';
}
