<?php declare(strict_types = 1);

namespace Tweakers\Common;

use Tweakers\Exception\InvalidEnumValue;

/**
 * This abstract class holds the methods for value objects that represent a value out of a
 * finite list of possible values. Like an enum field in mysql db.
 */
abstract class Enum
{
    /** @var string */
    protected $value;
    /** @var array Always use getAllowedValues() to access. */
    protected static $allowedValues = [];

    /**
     * @param string|static $value
     * @throws InvalidEnumValue
     */
    public function __construct($value)
    {
        $value = $value instanceof static ? $value->getValue() : $value;

        $this->validate($value);

        $this->value = $value;
    }

    /**
     * Create an instance from a string
     *
     * @param string $value
     * @return static
     */
    public static function fromString(string $value)
    {
        return new static($value);
    }

    /**
     * Validate the value on construction. This method can be overridden.
     *
     * @param string $value
     * @throws InvalidEnumValue
     */
    protected function validate(string $value)
    {
        if (! in_array($value, self::all())) {
            throw InvalidEnumValue::forConstructor(__CLASS__, $value);
        }
    }

    public static function has(string $value) : bool
    {
        return in_array($value, self::all());
    }

    public static function all() : array
    {
        // We cache the results because reflection is expensive. We have to store per class, because otherwise
        // the first implementation of Enum will determine the allowedValues for all Enum implementations.
        if (empty(static::$allowedValues[static::class])) {
            static::$allowedValues[static::class] = (new \ReflectionClass(static::class))->getConstants();
        }

        return static::$allowedValues[static::class];
    }

    /**
     * This function is only here for backwards compatibility.
     * @deprecated Preferred is the static method `all()`.
     */
    public function getAllowedValues() : array
    {
        return self::all();
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function lower() : string
    {
        return strtolower($this->value);
    }

    public function upper() : string
    {
        return strtoupper($this->value);
    }

    public function ucfirst() : string
    {
        return ucfirst($this->lower());
    }

    public function toString() : string
    {
        return (string) $this->value;
    }

    public function __toString() : string
    {
        return $this->toString();
    }

    public function jsonSerialize()
    {
        return $this->toString();
    }

    /**
     * Magic method to check if the implementation is of a certain value: $status->isDraft()
     *
     * You have to method hint your Enum implementation in the class docblock: `@method bool isCompleted`.
     *
     * @param string $name
     * @param mixed $params
     * @return bool
     */
    public function __call($name, $params)
    {
        $const = substr(strtoupper(snake_case($name)), 3);

        return $this->value == constant('static::' . $const);
    }

    /**
     * Magic constructor to create a new instance with the constant as method name: $status = Status::DRAFT();
     *
     * You have to method hint your Enum implementation in the class docblock: `@method static YourClass COMPLETED()`.
     *
     * @param $name
     * @param $params
     * @return static
     * @throws InvalidEnumValue
     */
    public static function __callStatic($name, $params)
    {
        if (! defined('static::' . $name)) {
            throw InvalidEnumValue::forMagicConstructor(__CLASS__, $name);
        }

        return new static(constant('static::' . $name));
    }
}
