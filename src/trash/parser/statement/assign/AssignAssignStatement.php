<?php


namespace trash\parser\statement\assign;


use trash\common\Environment;
use trash\common\value\Value;
use trash\lexer\TokenType;
use trash\parser\statement\Statement;

class AssignAssignStatement implements Statement{
    /**
     * @var AssignStatement
     */
    private $assign;
    /**
     * @var Statement
     */
    private $value;
    /**
     * @var int
     */
    private $operator;

    /**
     * AssignAssignStatement constructor.
     * @param AssignStatement $assign
     * @param Statement $value
     * @param int $operator
     */
    public function __construct(AssignStatement $assign, Statement $value, int $operator){
        $this->assign = $assign;
        $this->value = $value;
        $this->operator = $operator;
    }


    public function eval(Environment $environment): Value{
        return $this->assign->assign($environment, $this->value->eval($environment), $this->operator);
    }

    public function __toString(): string{
        $operator = TokenType::operatorToString($this->operator);
        return "{$this->assign} {$operator} {$this->value}";
    }
}