<?php


namespace trash\parser\statement\block;


use php\lib\str;

class ElseStatement extends BlockStatement{
    public function __toString(): string{
        $statements = str::join($this->getStatements(), ', ');
        return "ElseStatement\{{$statements}\}";
    }
}