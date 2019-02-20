<?php


namespace trash\common\value;


use php\util\Flow;
use trash\common\Environment;

class ArrayValue extends BaseValue{
    const NAME = 'array';

    /**
     * @var Value[]
     */
    private $values;


    public function __construct(Value ...$values){
        $this->values = $values;
    }

    public function add(Value $value){
        $this->values[] = $value;
    }
    public function put(Value $key, Value $value){
        $this->values[$this->toKey($key)] = $value;
    }

    private function toKey(Value $key){
        if($key instanceof IntegerValue){
            return $key->toInteger();
        }
        else{
            return $key->toString();
        }
    }

    function arrayGet(Environment $environment, Value $key): Value{
        return $this->values[$this->toKey($key)] ?? BaseValue::NULL();
    }

    function arraySet(Environment $environment, Value $key, Value $value): Value{
        $this->values[$this->toKey($key)] = $value;
        return $value;
    }


    function plus(Environment $environment, Value $other): Value{
        if($other instanceof ArrayValue){
            return new ArrayValue(...array_merge($this->values, $other->toArray()));
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
        return '['.
                Flow::of($this->values)->map(function($item, $i){
                    return "{$i}: {$item}";
                })->toString(', ')
            .']';
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
        return $this->values;
    }
}