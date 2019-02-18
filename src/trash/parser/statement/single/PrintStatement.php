<?php


namespace trash\parser\statement\single;


use php\lang\System;
use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\Value;
use trash\parser\statement\Statement;

class PrintStatement extends SingleStatement{

    public function eval(Environment $environment): Value{
        if($this->getValue()){
            echo "{$this->getValue()->eval($environment)->toString()}\n";
        }
        else{
            echo "\n";
        }

        return BaseValue::NULL();
    }

    public function __toString(): string{
        return "print {$this->getValue()}";
    }
}