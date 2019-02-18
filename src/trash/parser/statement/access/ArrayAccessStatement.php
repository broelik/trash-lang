<?php


namespace trash\parser\statement\access;


use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\assign\AssignStatement;
use trash\parser\statement\Statement;

class ArrayAccessStatement extends AssignStatement{
    /**
     * @var Statement
     */
    private $key;
    /**
     * @var Statement
     */
    private $target;

    /**
     * ArrayAccessStatement constructor.
     * @param Statement $key
     * @param Statement $target
     */
    public function __construct(Statement $key, Statement $target){
        $this->key = $key;
        $this->target = $target;
    }


    public function assign(Environment $environment, Value $value, int $operator): Value{
        $key = $this->eval($environment);
        $newValue = $this->wrapValue($environment, $key, $value, $operator);
        if(!$newValue){
            throw new \RuntimeException("Unable assign to {$this->key}");
        }
        return $this->target->eval($environment)->arraySet($environment, $key, $value);
    }

    public function eval(Environment $environment): Value{
        return $this->target->eval($environment)->arrayGet($environment, $this->key->eval($environment));
    }
}