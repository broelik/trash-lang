<?php


namespace trash\parser\statement;


use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\block\message\BreakMessage;
use trash\parser\statement\block\message\ContinueMessage;
use trash\parser\statement\block\message\ReturnMessage;

interface Statement{
    /**
     * @param Environment $environment
     * @return Value
     * @throws BreakMessage
     * @throws ContinueMessage
     * @throws ReturnMessage
     */
    public function eval(Environment $environment): Value;
}