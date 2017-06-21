<?php declare(strict_types=1);


namespace Tweakers\Exception;

final class InvalidEnumValue extends \Exception
{
    public static function forConstructor(string $class, string $value) : InvalidEnumValue
    {
        return new self("Invalid value provided to create " . $class . ": '$value'");
    }

    public static function forMagicConstructor(string $class, string $value) : InvalidEnumValue
    {
        return new self("Unable to use magic constructor to create " . $class . " with value: '$value'.");
    }
}
