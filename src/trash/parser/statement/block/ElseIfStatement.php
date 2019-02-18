<?php


namespace trash\parser\statement\block;


use php\lib\str;
use trash\common\Environment;
use trash\common\value\Value;

class ElseIfStatement extends SimpleExpressionBlock{
    public function __toString(): string{
        $statements = str::join($this->getStatements(), ', ');
        return "ElseIfStatement({$this->getExpression()})\{{$statements}\}";
    }
}