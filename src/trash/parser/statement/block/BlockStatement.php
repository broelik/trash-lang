<?php


namespace trash\parser\statement\block;


use php\lib\str;
use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\Value;
use trash\parser\statement\Statement;

class BlockStatement implements Statement{
    /**
     * @var Statement[]
     */
    private $statements = [];

    public function addStatement(Statement $statement){
        $this->statements[] = $statement;
    }

    /**
     * @return Statement[]
     */
    public function getStatements(): array{
        return $this->statements;
    }



    public function eval(Environment $environment): Value{
        foreach($this->statements as $statement){
            $statement->eval($environment);
        }

        return BaseValue::NULL();
    }

    public function __toString(): string{
        $name = (new \ReflectionClass($this))->getShortName();
        $statements = str::join($this->getStatements(), ', ');
        return "{$name}\{{$statements}\}";
    }
}