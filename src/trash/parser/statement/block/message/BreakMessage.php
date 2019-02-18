<?php


namespace trash\parser\statement\block\message;


use trash\common\value\BaseValue;
use trash\parser\statement\Statement;
use trash\parser\statement\value\ValueStatement;

class BreakMessage extends SystemMessage{
    public function __construct(){
        parent::__construct(null, 'Unexpected break statement');
    }
}