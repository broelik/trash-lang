<?php


namespace trash\common\value;


class NullValue extends BooleanValue{
    const NAME = 'null';

    public function __construct(){
        parent::__construct(false);
    }

    function booleanValue(): BooleanValue{
        return BaseValue::FALSE();
    }


    function getName(): string{
        return self::NAME;
    }
}