<?php


namespace trash\debug;


use php\io\Stream;
use php\lang\System;
use php\lib\str;
use trash\parser\statement\block\BlockStatement;
use trash\parser\statement\block\ForStatement;
use trash\parser\statement\block\FunctionDefStatement;
use trash\parser\statement\block\IfStatement;
use trash\parser\statement\block\SimpleExpressionBlock;
use trash\parser\statement\Statement;

class DebugStatementPrinter{
    /**
     * @param Statement $statement
     * @param Stream|null $out
     * @throws \ReflectionException
     * @throws \php\io\IOException
     */
    public function print(Statement $statement, ?Stream $out = null){
        $out = $out ?? System::out();
        $this->printStatement($statement, $out, 0);
    }

    /**
     * @param Statement $statement
     * @param Stream $out
     * @param int $indent
     * @throws \ReflectionException
     * @throws \php\io\IOException
     */
    private function printStatement(Statement $statement, Stream $out, int $indent){
        $indentString = \php\lib\str::repeat(' ', $indent);
        $reflection = new \ReflectionClass($statement);

        if($statement instanceof BlockStatement){
            if($statement instanceof SimpleExpressionBlock){
                $out->write($indentString."{$reflection->getShortName()}({$statement->getExpression()}):\n");
            }
            else if($statement instanceof FunctionDefStatement){
                $args = [];
                foreach($statement->getArgs() as $name => $arg){
                    if($arg == null){
                        $args[] = $name;
                    }
                    else{
                        $args[] = "{$name}={$arg}";
                    }
                }
                $argsString = str::join($args, ', ');
                $out->write($indentString."{$reflection->getShortName()} {$statement->getName()}({$argsString}):\n");
            }
            else if($statement instanceof ForStatement){
                $key = $statement->getKey();
                $value = $statement->getValue();
                $array = $statement->getArray();

                if(isset($key)){
                    $out->write($indentString."{$reflection->getShortName()}({$key}, {$value} : $array):\n");
                }
                else{
                    $out->write($indentString."{$reflection->getShortName()}({$value} : $array):\n");
                }
            }
            else{
                $out->write($indentString."{$reflection->getShortName()}:\n");
            }
            foreach($statement->getStatements() as $subStatement){
                $this->printStatement($subStatement, $out,$indent + 4);
            }
            if($statement instanceof IfStatement){
                foreach($statement->getElseIf() as $elseIfStatement){
                    $this->printStatement($elseIfStatement, $out, $indent);
                }
                if($statement->getElse()){
                    $this->printStatement($statement->getElse(), $out, $indent);
                }
            }
        }
        else{
            $out->write($indentString.(string)$statement."\n");
        }
    }
}