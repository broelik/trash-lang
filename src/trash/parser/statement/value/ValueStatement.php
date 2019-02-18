<?php


namespace trash\parser\statement\value;


use trash\common\Environment;
use trash\common\value\StringValue;
use trash\common\value\Value;
use trash\parser\statement\Statement;

class ValueStatement implements Statement{
    /**
     * @var Value
     */
    private $value;

    /**
     * ValueStatement constructor.
     * @param Value $value
     */
    public function __construct(Value $value){
        $this->value = $value;
    }

    public function eval(Environment $environment): Value{
        return $this->value;
    }

    public function __toString(): string{
        if($this->value instanceof StringValue){
            return '"'.$this->value->toString().'"';
        }
        return (string)$this->value;
    }
}