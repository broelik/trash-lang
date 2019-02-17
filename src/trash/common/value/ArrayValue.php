<?php


namespace trash\common\value;


use php\lib\arr;
use php\lib\str;
use php\util\Flow;
use trash\common\Environment;

class ArrayValue extends BaseValue{
    const NAME = 'array';

    /**
     * @var Value[]
     */
    private $value;


    public function __construct(Value ...$values){
        $this->value = $values;
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

    function toString(): string{
        return '['.str::join($this->value, ', ').']';
    }

    function toInteger(): int{
        throw new \RuntimeException("Unable cast {$this->getName()} to int");
    }

    function toFloat(): float{
        throw new \RuntimeException("Unable cast {$this->getName()} to float");
    }

    function toBoolean(): bool{
        return !empty($this->value);
    }
}