<?php


namespace trash\lexer;


class Token{
    /**
     * @var int
     */
    private $type;
    /**
     * @var string
     */
    private $value;
    /**
     * @var int
     */
    private $line;
    /**
     * @var int
     */
    private $linePosition;


    public function __construct(int $type, ?string $value = null){
        $this->type = $type;
        $this->value = $value;
    }

    public function getName(): string{
        return TokenType::getName($this->getType());
    }

    public function getType(): int{
        return $this->type;
    }

    public function getValue(): ?string{
        return $this->value;
    }

    /**
     * @return int
     */
    public function getLine(): int{
        return $this->line;
    }

    /**
     * @param int $line
     */
    public function setLine(int $line): void{
        $this->line = $line;
    }

    /**
     * @return int
     */
    public function getLinePosition(): int{
        return $this->linePosition;
    }

    /**
     * @param int $linePosition
     */
    public function setLinePosition(int $linePosition): void{
        $this->linePosition = $linePosition;
    }



    public function __toString(){
        $name = TokenType::getName($this->type);
        return isset($this->value) ? "{$name}({$this->value})" : $name;
    }
    public function __debugInfo(){
        $result = ['type' => TokenType::getName($this->type), 'line' => $this->line, 'position' => $this->linePosition];
        if(isset($this->value)){
            $result['value'] = $this->value;
        }


        return $result;
    }
}