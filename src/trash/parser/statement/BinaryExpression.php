<?php


namespace trash\parser\statement;


use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\Value;
use trash\lexer\TokenType;

class BinaryExpression implements Statement{
    /**
     * @var int
     */
    private $operator;
    /**
     * @var Statement
     */
    private $a;
    /**
     * @var Statement
     */
    private $b;

    /**
     * BinaryExpression constructor.
     * @param int $operator
     * @param Statement $a
     * @param Statement $b
     */
    public function __construct(int $operator, Statement $a, Statement $b){
        $this->operator = $operator;
        $this->a = $a;
        $this->b = $b;
    }


    public function eval(Environment $environment): Value{
        $a = $this->a->eval($environment);
        $b = $this->b->eval($environment);

        switch($this->operator){
            case TokenType::PLUS:
                return $a->plus($environment, $b);
            case TokenType::MINUS:
                return $a->minus($environment, $b);
            case TokenType::MUL:
                return $a->mul($environment, $b);
            case TokenType::DIV:
                return $a->div($environment, $b);
            case TokenType::REM:
                return $a->rem($environment, $b);

            case TokenType::EQ_EQ:
                return $a->equal($environment, $b);
            case TokenType::EQ_LESS:
                return $a->equalLess($environment, $b);
            case TokenType::EQ_MORE:
                return $a->equalMore($environment, $b);
            case TokenType::LESS:
                return $a->less($environment, $b);
            case TokenType::MORE:
                return $a->more($environment, $b);
            case TokenType::NOT_EQ:
                return $a->notEqual($environment, $b);
        }

        return BaseValue::NULL();
    }

    public function __toString(): string{
        $operator = TokenType::operatorToString($this->operator);
        return "{$this->a} {$operator} {$this->b}";
    }
}