<?php


namespace trash\parser\statement\value;


use php\util\Flow;
use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\Statement;

class InvokeStatement implements Statement{
    /**
     * @var Statement
     */
    private $target;
    /**
     * @var Statement[]
     */
    private $args = [];

    /**
     * InvokeStatement constructor.
     * @param Statement $target
     */
    public function __construct(Statement $target){
        $this->target = $target;
    }

    public function addArgument(Statement $statement){
        $this->args[] = $statement;
    }

    public function eval(Environment $environment): Value{
        $args = [];

        $target = $this->target->eval($environment);

        foreach($this->args as $arg){
            $args[] = $arg->eval($environment);
        }

        return $target->invoke($environment, ...$args);
    }

    public function __toString(): string{
        $args = Flow::of($this->args)->map(function(Statement $statement){
            return (string)$statement;
        })->toString(', ');
        return "{$this->target}({$args})";
    }
}