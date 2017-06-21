<?php declare(strict_types=1);

namespace Tweakers\Core;

/**
 * This entity represents a User
 */
class User
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id() : int
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }
}
