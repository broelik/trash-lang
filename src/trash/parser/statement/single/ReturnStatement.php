<?php


namespace trash\parser\statement\single;


use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\block\message\ReturnMessage;

class ReturnStatement extends SingleStatement{
    /**
     * @param Environment $environment
     * @return Value
     * @throws ReturnMessage
     */
    public function eval(Environment $environment): Value{
        throw new ReturnMessage($this->getValue());
    }

    public function __toString(): string{
        return "return {$this->getValue()}";
    }
}