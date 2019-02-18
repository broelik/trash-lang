<?php
//\php\lang\System::setProperty('file.encoding=', 'UTF-8');
//$lexer = \trash\lexer\Lexer::ofInput('data/simple.tsh');
//var_dump($lexer->lex());
$parser = \trash\parser\Parser::ofInput('data/simple.tsh');
$env = new \trash\common\Environment();
$env->setLocal('test_func', new \trash\common\value\FunctionValue(function(\trash\common\Environment $environment, \trash\common\value\Value ...$args){
    return \trash\common\value\IntegerValue::valueOf(123);
}));
$res = $parser->parse();

function printStatement(\trash\parser\statement\Statement $statement, int $indent){
    $indentString = \php\lib\str::repeat(' ', $indent);
    $reflection = new ReflectionClass($statement);

    if($statement instanceof \trash\parser\statement\block\BlockStatement){
        if($statement instanceof \trash\parser\statement\block\SimpleExpressionBlock){
            echo $indentString."{$reflection->getShortName()}({$statement->getExpression()}):\n";
        }
        else if($statement instanceof \trash\parser\statement\block\FunctionDefStatement){
            $args = [];
            foreach($statement->getArgs() as $name => $arg){
                if($arg == null){
                    $args[] = $name;
                }
                else{
                    $args[] = "{$name}={$arg}";
                }
            }
            $argsString = \php\lib\str::join($args, ', ');
            echo $indentString."{$reflection->getShortName()} {$statement->getName()}({$argsString}):\n";
        }
        else if($statement instanceof \trash\parser\statement\block\ForStatement){
            $key = $statement->getKey();
            $value = $statement->getValue();
            $array = $statement->getArray();

            if(isset($key)){
                echo $indentString."{$reflection->getShortName()}({$key}, {$value} : $array):\n";
            }
            else{
                echo $indentString."{$reflection->getShortName()}({$value} : $array):\n";
            }
        }
        else{
            echo $indentString."{$reflection->getShortName()}:\n";
        }
        foreach($statement->getStatements() as $subStatement){
            printStatement($subStatement, $indent + 4);
        }
        if($statement instanceof \trash\parser\statement\block\IfStatement){
            foreach($statement->getElseIf() as $elseIfStatement){
                printStatement($elseIfStatement, $indent);
            }
            if($statement->getElse()){
                printStatement($statement->getElse(), $indent);
            }
        }
    }
    else{
        echo $indentString.(string)$statement."\n";
    }
}

printStatement($res, 0);
$res->eval($env);
//var_dump($res->eval($env)->toString());
//var_dump($env);