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

class WhileStatement extends SimpleExpressionBlock{
    public function eval(Environment $environment): Value{
        while($this->getExpression()->eval($environment)->toBoolean()){
            try{
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