<?php


namespace trash\parser\statement\assign;


use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\Value;
use trash\lexer\TokenType;
use trash\parser\statement\block\message\BreakMessage;
use trash\parser\statement\block\message\ContinueMessage;
use trash\parser\statement\block\message\ReturnMessage;
use trash\parser\statement\Statement;

abstract class AssignStatement implements Statement{
    /**
     * @param Environment $environment
     * @param Value $value
     * @param int $operator
     * @return Value
     * @throws BreakMessage
     * @throws ContinueMessage
     * @throws ReturnMessage
     */
    public abstract function assign(Environment $environment, Value $value, int $operator): Value;

    protected function wrapValue(Environment $environment, ?Value $source, Value $new, int $operator): ?Value{
        if($operator == TokenType::EQ){
            return $new;
        }
        if($source == null){
            return null;
        }
        switch($operator){
            case TokenType::EQ:
                return $new;
            case TokenType::EQ_PLUS:
                return $source->plus($environment, $new);
            case TokenType::EQ_MINUS:
                return $source->minus($environment, $new);
            case TokenType::EQ_MUL:
                return $source->mul($environment, $new);
            case TokenType::EQ_DIV:
                return $source->div($environment, $new);
            case TokenType::EQ_REM:
                return $source->rem($environment, $new);
        }

        return null;
    }
}