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