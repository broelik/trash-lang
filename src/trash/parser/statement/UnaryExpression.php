<?php


namespace trash\parser\statement;


use trash\common\Environment;
use trash\common\value\Value;
use trash\lexer\TokenType;

class UnaryExpression implements Statement{
    /**
     * @var int
     */
    private $operator;
    /**
     * @var Statement
     */
    private $value;

    /**
     * UnaryExpression constructor.
     * @param int $operator
     * @param Statement $value
     */
    public function __construct($operator, Statement $value){
        $this->operator = $operator;
        $this->value = $value;
    }

    public function eval(Environment $environment): Value{
        $value = $this->value->eval($environment);

        switch($this->operator){
            case TokenType::NOT:
                return $value->not($environment);
            case TokenType::MINUS:
                return $value->invert($environment);
        }

        return null;
    }

    public function __toString(): string{
        $operator = TokenType::operatorToString($this->operator);
        return "{$operator}{$this->value}";
    }
}