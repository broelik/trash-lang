<?php


namespace trash\parser\statement\single;


use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\block\message\ContinueMessage;
use trash\parser\statement\Statement;

class ContinueStatement implements Statement{
    /**
     * @param Environment $environment
     * @return Value
     * @throws ContinueMessage
     */
    public function eval(Environment $environment): Value{
        throw new ContinueMessage();
    }

    public function __toString(): string{
        return "continue";
    }
}