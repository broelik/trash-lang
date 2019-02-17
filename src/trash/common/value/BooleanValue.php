<?php


namespace trash\common\value;


use trash\common\Environment;

class BooleanValue extends BaseValue{
    const NAME = 'bool';

    /**
     * @var bool
     */
    private $value;

    /**
     * BooleanValue constructor.
     * @param bool $value
     */
    public function __construct(bool $value){
        $this->value = $value;
    }


    function plus(Environment $environment, Value $other): Value{
        return $this->integerValue()->plus($environment, $other);
    }

    function minus(Environment $environment, Value $other): Value{
        return $this->integerValue()->minus($environment, $other);
    }

    function mul(Environment $environment, Value $other): Value{
        return $this->integerValue()->mul($environment, $other);
    }

    function div(Environment $environment, Value $other): Value{
        return $this->integerValue()->div($environment, $other);
    }

    function rem(Environment $environment, Value $other): Value{
        return $this->integerValue()->rem($environment, $other);
    }

    function equal(Environment $environment, Value $other): BooleanValue{
        return $this->integerValue()->equal($environment, $other);
    }

    function notEqual(Environment $environment, Value $other): BooleanValue{
        return $this->integerValue()->notEqual($environment, $other);
    }

    function less(Environment $environment, Value $other): BooleanValue{
        return $this->integerValue()->less($environment, $other);
    }

    function more(Environment $environment, Value $other): BooleanValue{
        return $this->integerValue()->more($environment, $other);
    }

    function equalLess(Environment $environment, Value $other): BooleanValue{
        return $this->integerValue()->equalLess($environment, $other);
    }

    function equalMore(Environment $environment, Value $other): BooleanValue{
        return $this->integerValue()->equalMore($environment, $other);
    }

    function not(): BooleanValue{
        return $this->value ? self::FALSE() : self::TRUE();
    }

    function invert(): Value{
        return $this->not();
    }

    function getName(): string{
        return self::NAME;
    }


    public static function valueOf(bool $value): BooleanValue{
        return $value ? BaseValue::TRUE() : BaseValue::FALSE();
    }


    function integerValue(): IntegerValue{
        return $this->value ? self::INT_1() : self::INT_0();
    }

    function floatValue(): FloatValue{
        return $this->value ? self::FLOAT_1() : self::FLOAT_0();
    }

    function booleanValue(): BooleanValue{
        return $this;
    }


    function toInteger(): int{
        return $this->value;
    }

    function toFloat(): float{
        return $this->value;
    }

    function toBoolean(): bool{
        return $this->value;
    }

    function toString(): string{
        return $this->value ? "true" : "false";
    }
}