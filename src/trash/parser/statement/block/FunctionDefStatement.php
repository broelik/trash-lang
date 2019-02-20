<?php


namespace trash\parser\statement\block;


use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\FunctionValue;
use trash\common\value\Value;
use trash\parser\statement\block\message\ReturnMessage;
use trash\parser\statement\Statement;

class FunctionDefStatement extends BlockStatement{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Statement[]
     */
    private $args = [];


    /**
     * FunctionDefStatement constructor.
     * @param string $name
     */
    public function __construct(string $name){
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }

    /**
     * @return Statement[]
     */
    public function getArgs(): array{
        return $this->args;
    }


    public function addArgument(string $name, ?Statement $statement){
        $this->args[$name] = $statement;
    }

    public function eval(Environment $environment): Value{
        $function = new FunctionValue(function(Environment $environment, Value ...$values){
            try{
                foreach($this->getStatements() as $statement){
                    $statement->eval($environment);
                }
                return BaseValue::NULL();
            }
            catch(ReturnMessage $returnMessage){
                return $returnMessage->getStatement()->eval($environment);
            }
        });
        foreach($this->args as $name => $arg){
            if($arg === null){
                $function->putArgument($name, null);
            }
            else{
                $function->putArgument($name, $arg->eval($environment));
            }
        }
        $environment->setLocal($this->name, $function);
        return BaseValue::NULL();
    }
}