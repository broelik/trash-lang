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
        $environment->setLocal($this->name, new FunctionValue(function(Environment $environment, Value ...$values){
            $i = 0;
            $env = new Environment($environment);
            foreach($this->args as $name => $arg){
                if(isset($values[$i])){
                    $env->setLocal($name, $values[$i]);
                }
                else if($arg !== null){
                    $env->setLocal($name, $arg->eval($environment));
                }
                else{
                    throw new \RuntimeException("Missing {$name} argument for function {$this->name}");
                }
                $i++;
            }
            try{
                return parent::eval($env);
            }
            catch(ReturnMessage $returnMessage){
                return $returnMessage->getStatement()->eval($env);
            }
        }));
        return BaseValue::NULL();
    }
}