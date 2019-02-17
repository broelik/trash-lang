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
        if($other instanceof ArrayValue){
            return new ArrayValue(...array_merge($this->value, $other->toArray()));
        }
        return $this->throwUnsupportedOperandException(__FUNCTION__, $other);
    }

    function equal(Environment $environment, Value $other): BooleanValue{
        return BooleanValue::valueOf($this->toArray() == $other->toArray());
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

    function toArray(): array{
        return $this->value;
    }
}