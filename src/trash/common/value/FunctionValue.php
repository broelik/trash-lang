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
     * @var array
     */
    private $args = [];

    /**
     * FunctionValue constructor.
     * @param callable $value
     */
    public function __construct(?callable $value = null){
        $this->value = $value;
    }

    public function putArgument(string $name, ?Value $defaultValue = null){
        $this->args[$name] = $defaultValue;
    }


    function getName(): string{
        return self::NAME;
    }

    function invoke(Environment $environment, Value ...$values): Value{
        $i = 0;
        $functionEnvironment = new Environment($environment);
        foreach($this->args as $name => $arg){
            if(isset($values[$i])){
                $functionEnvironment->setLocal($name, $values[$i]);
            }
            else if($arg !== null){
                if($arg instanceof ArrayValue){
                    $arg = new ArrayValue(...$arg->toArray());
                }
                $functionEnvironment->setLocal($name, $arg);
            }
            else{
                throw new \RuntimeException("Missing {$name} argument");
            }
            $i++;
        }

        $callback = $this->value;
        if($callback){
            return $callback($functionEnvironment, ...$values);
        }
        else{
            return parent::invoke($functionEnvironment, ...$values);
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