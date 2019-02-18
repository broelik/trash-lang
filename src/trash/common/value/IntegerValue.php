<?php


namespace trash\common\value;


use trash\common\Environment;

class IntegerValue extends BaseValue{
    const NAME = 'int';

    /**
     * @var int
     */
    private $value;

    /**
     * IntegerValue constructor.
     * @param int $value
     */
    public function __construct(int $value){
        $this->value = $value;
    }


    function plus(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue){
            return IntegerValue::valueOf($this->value + $other->toInteger());
        }
        else if($other instanceof FloatValue){
            return FloatValue::valueOf($this->value + $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function minus(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue){
            return IntegerValue::valueOf($this->value - $other->toInteger());
        }
        else if($other instanceof FloatValue){
            return FloatValue::valueOf($this->value - $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function mul(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue){
            return IntegerValue::valueOf($this->value * $other->toInteger());
        }
        else if($other instanceof FloatValue){
            return FloatValue::valueOf($this->value * $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function div(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue){
            return IntegerValue::valueOf($this->value / $other->toInteger());
        }
        else if($other instanceof FloatValue){
            return FloatValue::valueOf($this->value / $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function rem(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue){
            return IntegerValue::valueOf($this->value % $other->toInteger());
        }
        else if($other instanceof FloatValue){
            return FloatValue::valueOf($this->value % $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equal(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toInteger() == $other->toInteger());
        }
        return BooleanValue::FALSE();
    }

    function notEqual(Environment $environment, Value $other): BooleanValue{
        return $this->equal($environment, $other)->not();
    }

    function less(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toInteger() < $other->toInteger());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function more(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toInteger() > $other->toInteger());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equalLess(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toInteger() <= $other->toInteger());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equalMore(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toInteger() >= $other->toInteger());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }


    function invert(Environment $environment): Value{
        return self::valueOf(-$this->value);
    }

    function getName(): string{
        return self::NAME;
    }

    function integerValue(): IntegerValue{
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
        return $this->value;
    }


    public static function valueOf(int $value): IntegerValue{
        return new IntegerValue($value);
    }
}