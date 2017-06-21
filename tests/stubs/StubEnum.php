<?php declare(strict_types = 1);

namespace Tweakers\Stubs;

use Tweakers\Common\Enum;

/**
 * This stub is responsible for helping test the abstract Enum class.
 *
 * @method bool isDraft()
 * @method bool isOpen()
 * @method bool isClosed()
 *
 * @method static StubEnum DRAFT()
 * @method static StubEnum OPEN()
 * @method static StubEnum CLOSED()
 */
class StubEnum extends Enum
{
    const DRAFT  = 'draft';
    const OPEN   = 'open';
    const CLOSED = 'closed';
}
