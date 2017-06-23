<?php declare(strict_types=1);

namespace Tweakers\Exception;

class EntityNotFound extends \Exception
{
    public static function forEntity($name, $id) : EntityNotFound
    {
        return new self("Unable to find entity of type $name with id $id.");
    }
}
