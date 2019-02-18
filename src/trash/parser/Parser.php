<?php


namespace trash\parser;


use php\io\IOException;
use php\lib\arr;
use trash\common\value\BaseValue;
use trash\common\value\BooleanValue;
use trash\common\value\FloatValue;
use trash\common\value\IntegerValue;
use trash\common\value\StringValue;
use trash\lexer\Lexer;
use trash\lexer\LexerException;
use trash\lexer\Token;
use trash\lexer\TokenType;
use trash\parser\statement\access\ArrayAccessStatement;
use trash\parser\statement\assign\AssignAssignStatement;
use trash\parser\statement\assign\AssignStatement;
use trash\parser\statement\assign\NameStatement;
use trash\parser\statement\BinaryExpression;
use trash\parser\statement\block\BlockStatement;
use trash\parser\statement\block\ElseIfStatement;
use trash\parser\statement\block\ElseStatement;
use trash\parser\statement\block\ForStatement;
use trash\parser\statement\block\FunctionDefStatement;
use trash\parser\statement\block\IfStatement;
use trash\parser\statement\block\RootBlockStatement;
use trash\parser\statement\single\BreakStatement;
use trash\parser\statement\single\ContinueStatement;
use trash\parser\statement\single\PrintStatement;
use trash\parser\statement\single\ReturnStatement;
use trash\parser\statement\Statement;
use trash\parser\statement\UnaryExpression;
use trash\parser\statement\value\ArrayInitStatement;
use trash\parser\statement\value\InvokeStatement;
use trash\parser\statement\value\ValueStatement;

class Parser{
    /**
     * @var Token[]
     */
    private $tokens = [];
    /**
     * @var int
     */
    private $pos;
    /**
     * @var int
     */
    private $len;

    /**
     * Parser constructor.
     * @param Token[] $tokens
     */
    public function __construct(array $tokens){
        $this->tokens = $tokens;
        $this->len = arr::count($this->tokens);
    }

    /**
     * @return Statement
     * @throws ParserException
     */
    public function parse(): Statement{
        $this->pos = 0;
        $root = new RootBlockStatement();

        while($this->pos < $this->len){
            $block = $this->block();
            $root->addStatement($block);
            if(!($block instanceof BlockStatement)){
                $this->skipOperatorEnd();
            }
        }

        return $root;
    }

    /**
     * @param int $excepted
     * @throws ParserException
     */
    private function throwUnexpectedToken(int $excepted): void{
        $current = $this->current();
        $name = TokenType::getName($excepted);
        if($current){
            throw new ParserException("Excepted {$name} token, but {$current->getName()} token given on {$current->getLine()} at {$current->getLinePosition()}");
        }
        else{
            $this->throwUnexpectedEndOfFile();
        }
    }

    /**
     * @throws ParserException
     */
    private function throwUnexpectedEndOfFile(): void{
//        if(isset($excepted)){
//            $name = TokenType::getName($excepted);
//            throw new ParserException("Excepted {$name} token, ");
//        }
        throw new ParserException("Unexpected end of file");
    }

    /**
     * @param int $type
     * @throws ParserException
     * @return Token
     */
    private function skipOrException(int $type): Token{
        $result = $this->current();
        if(!$this->skip($type)){
            $this->throwUnexpectedToken($type);
        }
        return $result;
    }

    /**
     * @throws ParserException
     */
    private function skipOperatorEnd(): void{
        $this->skipOrException(TokenType::OPERATOR_END);
    }


    /**
     * @param BlockStatement $input
     * @return BlockStatement
     * @throws ParserException
     */
    private function collectBlock(BlockStatement $input): BlockStatement{
        $this->skip(TokenType::OPERATOR_END);
        $this->skipOrException(TokenType::OPEN_BRACE);

        while($this->pos < $this->len && !$this->skip(TokenType::CLOSE_BRACE)){
            $block = $this->block();
            $input->addStatement($block);
            if($this->skip(TokenType::CLOSE_BRACE)){
                break;
            }
            if(!($block instanceof BlockStatement)){
                $this->skipOperatorEnd();
            }
        }
        //$this->skipOperatorEnd();
        return $input;
    }


    /**
     * @param FunctionDefStatement $function
     * @throws ParserException
     */
    private function collectFunctionArguments(FunctionDefStatement $function): void{
        $this->skipOrException(TokenType::OPEN_RBRACE);
        if($this->skip(TokenType::CLOSE_RBRACE)){
            return;
        }

        while(true){
            $name = $this->skipOrException(TokenType::NAME);
            if($this->skip(TokenType::EQ)){
                $function->addArgument($name->getValue(), $this->expressionOrException());
            }
            else{
                $function->addArgument($name->getValue(), null);
            }

            if(!$this->skip(TokenType::COMMA)){
                break;
            }
        }
        $this->skipOrException(TokenType::CLOSE_RBRACE);
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function block(): ?Statement{
        $result = $this->single();

        if(!$result){
            // if
            if($this->skip(TokenType::IF)){
                $ifStatement = new IfStatement($this->expressionOrException());
                $this->collectBlock($ifStatement);

                while($this->skip(TokenType::ELIF)){
                    $elIfStatement = new ElseIfStatement($this->expressionOrException());
                    $this->collectBlock($elIfStatement);
                    $ifStatement->addElseIfStatement($elIfStatement);
                    // $ifStatement->addElseIfStatement($this->collectBlock(new ElseIfStatement($this->expressionOrException())));
                }
                if($this->skip(TokenType::ELSE)){
                    $elseStatement = new ElseStatement();
                    $this->collectBlock($elseStatement);
                    $ifStatement->setElse($elseStatement);
                }
                return $ifStatement;
            }
            // function def
            else if($this->skip(TokenType::FUNCTION)){
                $nameToken = $this->skipOrException(TokenType::NAME);
                $block = new FunctionDefStatement($nameToken->getValue());
                $this->collectFunctionArguments($block);

                return $this->collectBlock($block);
            }
            else if($this->skip(TokenType::FOR)){
                $this->skipOrException(TokenType::OPEN_RBRACE);
                $valueOrKey = $this->skipOrException(TokenType::NAME);

                if($this->skip(TokenType::COMMA)){
                    $key = $valueOrKey->getValue();
                    $value = $this->skipOrException(TokenType::NAME)->getValue();
                }
                else{
                    $key = null;
                    $value = $valueOrKey->getValue();
                }
                $this->skipOrException(TokenType::COLON);
                $array = $this->expressionOrException();
                $this->skipOrException(TokenType::CLOSE_RBRACE);

                $for = new ForStatement($key, $value, $array);
                return $this->collectBlock($for);
            }
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function single(): ?Statement{
        $result = $this->expression();

        if(!$result){
            if($this->skip(TokenType::PRINT)){
                return new PrintStatement($this->expression());
            }
            else if($this->skip(TokenType::RETURN)){
                return new ReturnStatement($this->expression());
            }
            else if($this->skip(TokenType::BREAK)){
                return new BreakStatement();
            }
            else if($this->skip(TokenType::CONTINUE)){
                return new ContinueStatement();
            }
        }

        return $result;
    }


    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function expressionOrException(): ?Statement{
        return $this->expression();
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function expression(): ?Statement{
        $result = $this->logic();

        return $result;
    }


    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function logic(): ?Statement{
        $result = $this->plusMinus();

        if($result != null){
            $operatorToken = $this->current();
            while(
                $this->skip(TokenType::EQ_EQ) || $this->skip(TokenType::EQ_LESS) || $this->skip(TokenType::EQ_MORE) ||
                $this->skip(TokenType::LESS) || $this->skip(TokenType::MORE) || $this->skip(TokenType::NOT_EQ)
            ){
                $result = new BinaryExpression($operatorToken->getType(), $result, $this->mulDiv());
                $operatorToken = $this->current();
            }
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function plusMinus(): ?Statement{
        $result = $this->mulDiv();

        if($result != null){
            $operatorToken = $this->current();
            while($this->skip(TokenType::PLUS) || $this->skip(TokenType::MINUS)){
                $result = new BinaryExpression($operatorToken->getType(), $result, $this->mulDiv());
                $operatorToken = $this->current();
            }
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function mulDiv(): ?Statement{
        $result = $this->unary();

        if($result != null){
            $operatorToken = $this->current();
            while($this->skip(TokenType::MUL) || $this->skip(TokenType::DIV) || $this->skip(TokenType::REM)){
                $result = new BinaryExpression($operatorToken->getType(), $result, $this->unary());
                $operatorToken = $this->current();
            }
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function unary(): ?Statement{
        $result = $this->assign();

        $operatorToken = $this->current();
        if(!$result){
            if($this->skip(TokenType::NOT) || $this->skip(TokenType::MINUS)){
                return new UnaryExpression($operatorToken->getType(), $this->expressionOrException());
            }
        }

        return $result;
    }


    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function assign(): ?Statement{
        $result = $this->nameAssign();

        $operatorToken = $this->current();
        if($result instanceof AssignStatement && (
            $this->skip(TokenType::EQ) || $this->skip(TokenType::EQ_PLUS) || $this->skip(TokenType::EQ_MINUS) ||
            $this->skip(TokenType::EQ_MUL) || $this->skip(TokenType::DIV) || $this->skip(TokenType::REM)
            ))
        {
            return new AssignAssignStatement($result, $this->expressionOrException(), $operatorToken->getType());
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function nameAssign(): ?Statement{
        $result = $this->name();

        if($result){
            while(true){
                // array access
                if($this->skip(TokenType::OPEN_SBRACE)){
                    $key = $this->expressionOrException();
                    if(!$this->skip(TokenType::CLOSE_SBRACE)){
                        throw new ParserException("Brace not close");
                    }
                    $result = new ArrayAccessStatement($result, $key);
                }
                // invoke
                else if($this->skip(TokenType::OPEN_RBRACE)){
                    $invokeStatement = new InvokeStatement($result);

                    if($this->skip(TokenType::CLOSE_RBRACE)){
                        return $invokeStatement;
                    }

                    while(true){
                        $invokeStatement->addArgument($this->expressionOrException());

                        if(!$this->skip(TokenType::COMMA)){
                            break;
                        }
                    }
                    if(!$this->skip(TokenType::CLOSE_RBRACE)){
                        throw new ParserException("Arguments not closed");
                    }
                    $result = $invokeStatement;
                }
                else{
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function name(): ?Statement{
        $result = $this->array();

        $nameToken = $this->current();
        if(!$result && $this->skip(TokenType::NAME)){
            return new NameStatement($nameToken->getValue());
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function array(): ?Statement{
        $result = $this->primary();

        if(!$result){
            if($this->skip(TokenType::OPEN_SBRACE)){
                $array = new ArrayInitStatement();

                if($this->skip(TokenType::CLOSE_SBRACE)){
                    return $array;
                }

                while(true){
                    $value = $this->expressionOrException();
                    if($this->skip(TokenType::COLON)){
                        $array->putStatement($value, $this->expressionOrException());
                    }
                    else{
                        $array->addStatement($value);
                    }

                    if(!$this->skip(TokenType::COMMA)){
                        break;
                    }
                }
                $this->skipOrException(TokenType::CLOSE_SBRACE);

                return $array;
            }
        }

        return $result;
    }

    /**
     * @return Statement|null
     * @throws ParserException
     */
    private function primary(): ?Statement{
        $token = $this->current();

        if(!$token){
            return null;
        }

        switch($token->getType()){
            case TokenType::INT:
                $this->pop();
                return new ValueStatement(IntegerValue::valueOf($token->getValue()));
            case TokenType::FLOAT:
                $this->pop();
                return new ValueStatement(FloatValue::valueOf($token->getValue()));
            case TokenType::STRING:
                $this->pop();
                return new ValueStatement(StringValue::valueOf($token->getValue()));
            case TokenType::TRUE:
            case TokenType::FALSE:
                $this->pop();
                return new ValueStatement(BooleanValue::valueOf($token->getType() == TokenType::TRUE));
            case TokenType::NULL:
                $this->pop();
                return new ValueStatement(BaseValue::NULL());
            case TokenType::OPEN_RBRACE:
                $this->pop();
                $result = $this->expressionOrException();
                if(!$this->skip(TokenType::CLOSE_RBRACE)){
                    throw new ParserException("Brace not closed");
                }
                return $result;
        }

        return null;
    }


    private function is(int $type, int $amount = 0): bool{
        $token = $this->peek($amount);
        return $token && $token->getType() == $type;
    }
    private function skip(int $type): bool{
        if($this->is($type)){
            $this->pop();
            return true;
        }
        return false;
    }


    private function peek(int $amount): ?Token{
        $pos = $this->pos + $amount;
        return $this->tokens[$pos];
    }
    private function current(): ?Token{
        return $this->peek(0);
    }
    private function jump(int $amount): ?Token{
        $this->pos += $amount;
        return $this->current();
    }
    private function pop(): ?Token{
        return $this->jump(1);
    }

    /**
     * @param $input
     * @return Parser
     * @throws IOException
     * @throws LexerException
     */
    public static function ofInput($input): Parser{
        return new Parser(Lexer::ofInput($input)->lex());
    }
}