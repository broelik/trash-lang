<?php


namespace trash\debug;


use php\lib\arr;
use php\lib\str;
use php\util\Scanner;
use trash\common\Environment;
use trash\common\value\FloatValue;
use trash\common\value\FunctionValue;
use trash\common\value\IntegerValue;
use trash\common\value\Value;
use trash\lexer\Lexer;
use trash\parser\Parser;
use trash\parser\statement\assign\NameStatement;
use trash\parser\statement\block\message\SystemMessage;
use trash\parser\statement\value\ValueStatement;

class DebugLauncher{
    private $args = [];
    private $flags = [];

    private $file;


    function __construct(array $args){
        $this->args = flow($args)->find(function ($arg) {
            if (str::startsWith($arg, "--")) {
                $this->flags[str::sub($arg, 2)] = true;
                return false;
            }
            if (str::startsWith($arg, "-")) {
                $this->flags[str::sub($arg, 1)] = true;
                return false;
            }
            return true;
        })->toArray();
        arr::shift($this->args);

        $this->file = str::join($this->args, ' ');
    }

    /**
     * @throws \ReflectionException
     * @throws \php\io\IOException
     * @throws \trash\lexer\LexerException
     * @throws \trash\parser\ParserException
     * @throws SystemMessage
     */
    function launch(){
        $parser = Parser::ofInput($this->file);

        $env = new Environment();
        $env->setLocal('count', new FunctionValue(function(Environment $environment, Value ...$values){
            return IntegerValue::valueOf(count($values[0]->toArray()));
        }));

        $statement = $parser->parse();

        if($this->flags['d']){
            $debugPrinter = new DebugStatementPrinter();
            $debugPrinter->print($statement);
        }
        else{
            $statement->eval($env);
        }
    }
}