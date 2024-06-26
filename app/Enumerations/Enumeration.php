<?php

namespace App\Enumerations;

use ReflectionClass;

abstract class Enumeration
{

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = [];
    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     * @param bool $strict
     */
    public function __construct($value, $strict = true)
    {
        if (!$this->isValid($value, $strict)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }

        $this->value = static::toArray()[$this->search($value, $strict)];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     * @param bool $strict
     * @return bool
     */
    public static function isValid($value, $strict = true)
    {
        return in_array($value, static::toArray(), $strict);
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     * @throws
     */
    public static function toArray()
    {
        $class = get_called_class();
        if (!array_key_exists($class, static::$cache)) {
            $reflection = new ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     */
    public static function keys()
    {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values()
    {
        $values = [];

        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Check if is valid enum key
     *
     * @param $key
     * @return bool
     */
    public static function isValidKey($key)
    {
        $array = static::toArray();

        return isset($array[$key]);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array $arguments
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (isset($array[$name])) {
            return new static($array[$name]);
        }

        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . get_called_class());
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @param boolean $strict
     * @return mixed
     */
    public function getKey($strict = true)
    {
        return static::search($this->value, $strict);
    }

    /**
     * Return key for value
     *
     * @param $value
     * @param $strict
     * @return mixed
     */
    public static function search($value, $strict = true)
    {
        return array_search($value, static::toArray(), $strict);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @param Enumeration $enum
     * @return bool True if Enums are equal, false if not equal
     */
    final public function equals(Enumeration $enum)
    {
        return $this->getValue() === $enum->getValue();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
