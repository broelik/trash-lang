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
        // TODO: Implement plus() method.
    }

    function minus(Environment $environment, Value $other): Value{
        // TODO: Implement minus() method.
    }

    function mul(Environment $environment, Value $other): Value{
        // TODO: Implement mul() method.
    }

    function div(Environment $environment, Value $other): Value{
        // TODO: Implement div() method.
    }

    function rem(Environment $environment, Value $other): Value{
        // TODO: Implement rem() method.
    }

    function equal(Environment $environment, Value $other): Value{
        // TODO: Implement equal() method.
    }

    function notEqual(Environment $environment, Value $other): Value{
        // TODO: Implement notEqual() method.
    }

    function less(Environment $environment, Value $other): Value{
        // TODO: Implement less() method.
    }

    function more(Environment $environment, Value $other): Value{
        // TODO: Implement more() method.
    }

    function equalLess(Environment $environment, Value $other): Value{
        // TODO: Implement equalLess() method.
    }

    function equalMore(Environment $environment, Value $other): Value{
        // TODO: Implement equalMore() method.
    }

    function not(): Value{
        // TODO: Implement not() method.
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