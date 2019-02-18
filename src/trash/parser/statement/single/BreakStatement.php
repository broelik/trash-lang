<?php


namespace trash\parser\statement\single;


use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\block\message\BreakMessage;
use trash\parser\statement\Statement;

class BreakStatement implements Statement{
    /**
     * @param Environment $environment
     * @return Value
     * @throws BreakMessage
     */
    public function eval(Environment $environment): Value{
        throw new BreakMessage();
    }

    public function __toString(): string{
        return "break";
    }
}