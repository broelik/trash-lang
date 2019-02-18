<?php


namespace trash\common\value;


use trash\common\Environment;

class FunctionValue extends BaseValue{
    const NAME = 'func';

    /**
     * @var callable
     */
    private $value;

    /**
     * FunctionValue constructor.
     * @param callable $value
     */
    public function __construct(?callable $value = null){
        $this->value = $value;
    }


    function getName(): string{
        return self::NAME;
    }

    function invoke(Environment $environment, Value ...$values): Value{
        $callback = $this->value;
        if($callback){
            return $callback($environment, ...$values);
        }
        else{
            return parent::invoke($environment, ...$values);
        }
    }


    function toString(): string{
        return 'function';
    }

    function toInteger(): int{
        throw new \RuntimeException("Unable cast {$this->getName()} to int");
    }

    function toFloat(): float{
        throw new \RuntimeException("Unable cast {$this->getName()} to float");
    }

    function toBoolean(): bool{
        return true;
    }
}