<?php


namespace trash\parser\statement\assign;


use trash\common\Environment;
use trash\common\value\Value;

class NameStatement extends AssignStatement{
    /**
     * @var string
     */
    private $name;

    /**
     * NameStatement constructor.
     * @param string $name
     */
    public function __construct(string $name){
        $this->name = $name;
    }

    public function assign(Environment $environment, Value $value, int $operator): Value{
        $newValue = $this->wrapValue($environment, $environment->getLocal($this->name), $value, $operator);
        if(!$newValue){
            throw new \RuntimeException("Unable assign to {$this->name}");
        }
        return $environment->setLocal($this->name, $newValue);
    }

    public function eval(Environment $environment): Value{
        $result = $environment->getLocal($this->name);
        if(!$result){
            throw new \RuntimeException("Undefined variable $this->name");
        }
        return $result;
    }

    public function __toString(): string{
        return $this->name;
    }
}