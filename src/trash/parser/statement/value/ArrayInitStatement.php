<?php


namespace trash\parser\statement\value;


use php\lib\str;
use php\util\Flow;
use trash\common\Environment;
use trash\common\value\ArrayValue;
use trash\common\value\Value;
use trash\parser\statement\Statement;

class ArrayInitStatement implements Statement{
    /**
     * @var Statement[][]
     */
    private $statements = [];

    public function addStatement(Statement $statement): void{
        $this->statements[] = [$statement];
    }
    public function putStatement(Statement $key, Statement $statement){
        $this->statements[] = [$statement, $key];
    }

    public function eval(Environment $environment): Value{
        $result = new ArrayValue();

        foreach($this->statements as $statement){
            if(count($statement) > 1){
                $result->put($statement[1]->eval($environment), $statement[0]->eval($environment));
            }
            else{
                $result->add($statement[0]->eval($environment));
            }
        }

        return $result;
    }

    public function __toString(): string{
        return '['.
                (Flow::of($this->statements)->map(function($value){
                    if(count($value) > 1){
                        return "{$value[1]}: {$value[0]}";
                    }
                    else{
                        return (string)$value[0];
                    }
                }))
                ->toString(', ')
            .']';
    }
}