<?php


namespace trash\common\value;


use trash\common\Environment;

class FloatValue extends BaseValue{
    const NAME = 'float';

    /**
     * @var float
     */
    private $value;

    /**
     * FloatValue constructor.
     * @param float $value
     */
    public function __construct(float $value){
        $this->value = $value;
    }


    function plus(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue || $other instanceof FloatValue){
            return FloatValue::valueOf($this->value + $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function minus(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue || $other instanceof FloatValue){
            return FloatValue::valueOf($this->value - $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function mul(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue || $other instanceof FloatValue){
            return FloatValue::valueOf($this->value * $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function div(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue || $other instanceof FloatValue){
            return FloatValue::valueOf($this->value / $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function rem(Environment $environment, Value $other): Value{
        if($other instanceof IntegerValue || $other instanceof BooleanValue || $other instanceof FloatValue){
            return FloatValue::valueOf($this->value % $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equal(Environment $environment, Value $other): BooleanValue{
        return BooleanValue::valueOf($this->toFloat() == $other->toFloat());
    }

    function notEqual(Environment $environment, Value $other): BooleanValue{
        return $this->equal($environment, $other)->not();
    }

    function less(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toFloat() < $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function more(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toFloat() > $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equalLess(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toFloat() <= $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equalMore(Environment $environment, Value $other): BooleanValue{
        if($other instanceof IntegerValue || $other instanceof FloatValue || $other instanceof BooleanValue){
            return BooleanValue::valueOf($this->toFloat() >= $other->toFloat());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function not(): BooleanValue{
        return $this->booleanValue()->not();
    }

    function invert(): Value{
        return self::valueOf(-$this->value);
    }

    function getName(): string{
        return self::NAME;
    }

    public static function valueOf(float $value): FloatValue{
        return new FloatValue($value);
    }

    function floatValue(): FloatValue{
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

}