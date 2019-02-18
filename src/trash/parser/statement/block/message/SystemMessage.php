<?php


namespace trash\parser\statement\block\message;


use trash\parser\statement\Statement;

class SystemMessage extends \Exception{
    /**
     * @var Statement|null
     */
    private $statement;

    /**
     * SystemMessage constructor.
     * @param Statement|null $statement
     * @param string $message
     */
    public function __construct(?Statement $statement, string $message){
        parent::__construct($message);
        $this->statement = $statement;
    }


    /**
     * @return Statement|null
     */
    public function getStatement(): ?Statement{
        return $this->statement;
    }

    /**
     * @param Statement|null $statement
     */
    public function setStatement(?Statement $statement): void{
        $this->statement = $statement;
    }
}