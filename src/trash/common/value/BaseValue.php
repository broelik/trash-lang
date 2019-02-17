<?php


namespace trash\common\value;


use trash\common\Environment;

abstract class BaseValue implements Value{
    function arrayGet(Environment $environment, string $key): Value{
        throw new \RuntimeException("Unable to use {$this->getName()} as array");
    }

    function arraySet(Environment $environment, string $key, Value $value): Value{
        throw new \RuntimeException("Unable to use {$this->getName()} as array");
    }

    function invoke(Environment $environment, Value ...$values): Value{
        throw new \RuntimeException("Unable to invoke {$this->getName()}");
    }

    public final function __toString(){
        return $this->toString();
    }

    function stringValue(): StringValue{
        return StringValue::valueOf($this->toString());
    }

    function integerValue(): IntegerValue{
        return IntegerValue::valueOf($this->toInteger());
    }

    function floatValue(): FloatValue{
        return FloatValue::valueOf($this->toFloat());
    }

    function booleanValue(): BooleanValue{
        return BooleanValue::valueOf($this->toBoolean());
    }

    function arrayValue(): ArrayValue{
        return new ArrayValue($this->toArray());
    }

    function toArray(): array{
        throw new \RuntimeException("Unable cast {$this->getName()} to array");
    }


    /**
     * @var BooleanValue
     */
    private static $TRUE;
    /**
     * @var BooleanValue
     */
    private static $FALSE;
    /**
     * @var NullValue
     */
    private static $NULL;

    /**
     * @var IntegerValue
     */
    private static $INT_0;
    /**
     * @var IntegerValue
     */
    private static $INT_1;

    /**
     * @var FloatValue
     */
    private static $FLOAT_0;
    /**
     * @var FloatValue
     */
    private static $FLOAT_1;


    public static final function TRUE(): BooleanValue{
        return isset(self::$TRUE) ? self::$TRUE : (self::$TRUE = new BooleanValue(true));
    }
    public static final function FALSE(): BooleanValue{
        return isset(self::$FALSE) ? self::$FALSE : (self::$FALSE = new BooleanValue(false));
    }

    public static final function NULL(): NullValue{
        return isset(self::$NULL) ? self::$NULL : (self::$NULL = new NullValue());
    }

    public static final function INT_0(): IntegerValue{
        return isset(self::$INT_0) ? self::$INT_0 : (self::$INT_0 = IntegerValue::valueOf(0));
    }
    public static final function INT_1(): IntegerValue{
        return isset(self::$INT_1) ? self::$INT_1 : (self::$INT_1 = IntegerValue::valueOf(1));
    }

    public static final function FLOAT_0(): FloatValue{
        return isset(self::$FLOAT_0) ? self::$FLOAT_0 : (self::$FLOAT_0 = FloatValue::valueOf(0));
    }
    public static final function FLOAT_1(): FloatValue{
        return isset(self::$FLOAT_1) ? self::$FLOAT_1 : (self::$FLOAT_1 = FloatValue::valueOf(1));
    }
}