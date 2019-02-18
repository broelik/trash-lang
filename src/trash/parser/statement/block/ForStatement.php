<?php


namespace trash\parser\statement\block;


use trash\common\Environment;
use trash\common\value\BaseValue;
use trash\common\value\IntegerValue;
use trash\common\value\StringValue;
use trash\common\value\Value;
use trash\parser\statement\block\message\BreakMessage;
use trash\parser\statement\block\message\ContinueMessage;
use trash\parser\statement\block\message\ReturnMessage;
use trash\parser\statement\Statement;

class ForStatement extends BlockStatement{
    /**
     * @var string|null
     */
    private $key;
    /**
     * @var string
     */
    private $value;
    /**
     * @var Statement
     */
    private $array;

    /**
     * ForStatement constructor.
     * @param string|null $key
     * @param string $value
     * @param Statement $array
     */
    public function __construct(?string $key, string $value, Statement $array){
        $this->key = $key;
        $this->value = $value;
        $this->array = $array;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string{
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string{
        return $this->value;
    }

    /**
     * @return Statement
     */
    public function getArray(): Statement{
        return $this->array;
    }

    public function eval(Environment $environment): Value{
        foreach($this->array->eval($environment)->toArray() as $key => $value){
            try{
                if(isset($this->key)){
                    $key = is_int($key) ? IntegerValue::valueOf($key) : StringValue::valueOf($key);
                    $environment->setLocal($this->key, $key);
                }
                $environment->setLocal($this->value, $value);
                parent::eval($environment);
            }
            catch(BreakMessage $breakMessage){
                break;
            }
            catch(ContinueMessage $returnMessage){
                continue;
            }
        }

        return BaseValue::NULL();
    }
}