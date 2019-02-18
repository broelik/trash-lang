<?php


namespace trash\parser\statement\block;


use trash\parser\statement\Statement;

class SimpleExpressionBlock extends BlockStatement{
    /**
     * @var Statement
     */
    private $expression;

    /**
     * SimpleExpressionBlock constructor.
     * @param Statement $expression
     */
    public function __construct(Statement $expression){
        $this->expression = $expression;
    }

    /**
     * @return Statement
     */
    public function getExpression(): Statement{
        return $this->expression;
    }
}