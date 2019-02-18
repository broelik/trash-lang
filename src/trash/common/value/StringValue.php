<?php


namespace trash\common\value;


use trash\common\Environment;

class StringValue extends BaseValue{
    const NAME = 'string';

    /**
     * @var string
     */
    private $value;

    /**
     * StringValue constructor.
     * @param string $value
     */
    public function __construct(string $value){
        $this->value = $value;
    }


    function plus(Environment $environment, Value $other): Value{
        if($other instanceof StringValue){
            return StringValue::valueOf($this->value.$other->toString());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equal(Environment $environment, Value $other): BooleanValue{
        if($other instanceof StringValue){
            return BooleanValue::valueOf($this->toString() == $other->toString());
        }
        return self::FALSE();
    }

    function notEqual(Environment $environment, Value $other): BooleanValue{
        return $this->equalMore($environment, $other)->not();
    }

    function less(Environment $environment, Value $other): BooleanValue{
        if($other instanceof StringValue){
            return BooleanValue::valueOf($this->toString() < $other->toString());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function more(Environment $environment, Value $other): BooleanValue{
        if($other instanceof StringValue){
            return BooleanValue::valueOf($this->toString() > $other->toString());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equalLess(Environment $environment, Value $other): BooleanValue{
        if($other instanceof StringValue){
            return BooleanValue::valueOf($this->toString() <= $other->toString());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equalMore(Environment $environment, Value $other): BooleanValue{
        if($other instanceof StringValue){
            return BooleanValue::valueOf($this->toString() >= $other->toString());
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }



    function invert(Environment $environment): Value{
        return $this->throwUnsupportedOperandException(__FUNCTION__);
    }

    function getName(): string{
        return self::NAME;
    }

    function stringValue(): StringValue{
        return $this;
    }

    public function toString(): string {
        return $this->value;
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

    public static function valueOf(string $value): StringValue{
        return new StringValue($value);
    }
}