<?php


namespace trash\parser\statement\block\message;


use trash\common\value\BaseValue;
use trash\parser\statement\Statement;
use trash\parser\statement\value\ValueStatement;

class ContinueMessage extends SystemMessage{
    public function __construct(?Statement $statement = null){
        parent::__construct(null, 'Unexpected continue statement');
    }
}