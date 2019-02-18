<?php


namespace trash\parser\statement\single;


use trash\common\Environment;
use trash\common\value\Value;
use trash\parser\statement\Statement;

abstract class SingleStatement implements Statement{
    /**
     * @var Statement|null
     */
    private $value;

    /**
     * SingleStatement constructor.
     * @param Statement|null $value
     */
    public function __construct(?Statement $value){
        $this->value = $value;
    }

    /**
     * @return Statement|null
     */
    public function getValue(): ?Statement{
        return $this->value;
    }
}