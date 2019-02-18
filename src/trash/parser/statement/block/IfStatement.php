<?php


namespace trash\parser\statement\block;


use php\lib\arr;
use php\lib\str;
use php\util\Flow;
use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\Value;
use trash\parser\statement\Statement;

class IfStatement extends SimpleExpressionBlock{
    /**
     * @var ElseIfStatement[]
     */
    private $elseIf = [];
    /**
     * @var ElseStatement|null
     */
    private $else;

    /**
     * @param ElseStatement $else
     */
    public function setElse(ElseStatement $else): void{
        $this->else = $else;
    }
    public function addElseIfStatement(ElseIfStatement $statement): void{
        $this->elseIf[] = $statement;
    }

    /**
     * @return ElseIfStatement[]
     */
    public function getElseIf(): array{
        return $this->elseIf;
    }

    /**
     * @return ElseStatement
     */
    public function getElse(): ?ElseStatement{
        return $this->else;
    }


    public function eval(Environment $environment): Value{
        if($this->getExpression()->eval($environment)->toBoolean()){
            return parent::eval($environment);
        }
        else{
            foreach($this->elseIf as $elseIfStatement){
                if($elseIfStatement->getExpression()->eval($environment)->toBoolean()){
                    return $elseIfStatement->eval($environment);
                }
            }
        }
        if($this->else){
            return $this->else->eval($environment);
        }

        return BaseValue::NULL();
    }

    public function __toString(): string{
        $statements = str::join($this->getStatements(), ', ');
        $if = "IfStatement({$this->getExpression()})\{{$statements}\}";

        $args = Flow::of($this->elseIf)->map(function(ElseIfStatement $elseIfStatement){
            return (string)$elseIfStatement;
        })->toArray(false);
        arr::unshift($args, $if);
        if($this->else){
            $args[] = (string)$this->else;
        }
        return str::join($args, "\n");
    }


}