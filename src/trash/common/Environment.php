<?php


namespace trash\common;


use trash\common\value\Value;

class Environment{
    /**
     * @var Value[]
     */
    private $locals = [];


    public function getLocal(string $name): ?Value{
        return $this->locals[$name];
    }
    public function setLocal(string $name, ?Value $value): ?Value{
        $this->locals[$name] = $value;
        return $value;
    }
}