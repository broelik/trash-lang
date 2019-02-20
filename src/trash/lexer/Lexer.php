<?php


namespace trash\lexer;


use php\io\IOException;
use php\io\Stream;
use php\lib\arr;
use php\lib\char;
use php\lib\str;

class Lexer{
    // словарь операторов
    private static $OPERATORS = [
        "\n" => TokenType::OPERATOR_END,
        '{' => TokenType::OPEN_BRACE,
        '}' => TokenType::CLOSE_BRACE,
        '(' => TokenType::OPEN_RBRACE,
        ')' => TokenType::CLOSE_RBRACE,
        '[' => TokenType::OPEN_SBRACE,
        ']' => TokenType::CLOSE_SBRACE,

        '+' => TokenType::PLUS,
        '-' => TokenType::MINUS,
        '*' => TokenType::MUL,
        '/' => TokenType::DIV,
        '%' => TokenType::REM,

        '=' => TokenType::EQ,
        '+=' => TokenType::EQ_PLUS,
        '-=' => TokenType::EQ_MINUS,
        '*=' => TokenType::EQ_MUL,
        '/=' => TokenType::EQ_DIV,
        '%=' => TokenType::EQ_REM,

        '==' => TokenType::EQ_EQ,
        '!=' => TokenType::NOT_EQ,
        '<' => TokenType::LESS,
        '>' => TokenType::MORE,
        '<=' => TokenType::EQ_LESS,
        '>=' => TokenType::EQ_MORE,
        '!' => TokenType::NOT,


        ',' => TokenType::COMMA,
        '.' => TokenType::POINT,
        ':' => TokenType::COLON,
        ';' => TokenType::OPERATOR_END,

        '..' => TokenType::UNDEFINED,
        '...' => TokenType::ELLIPSIS,
    ];
    // словарь ключевых слов
    private static $KEYWORDS = [
        'not' => TokenType::NOT,

        'if' => TokenType::IF,
        'elif' => TokenType::ELIF,
        'else' => TokenType::ELSE,
        'for' => TokenType::FOR,
        'while' => TokenType::WHILE,
        'with' => TokenType::WITH,
        'func' => TokenType::FUNCTION,

        'return' => TokenType::RETURN,
        'print' => TokenType::PRINT,
        'break' => TokenType::BREAK,
        'continue' => TokenType::CONTINUE,

        'null' => TokenType::NULL,
        'true' => TokenType::TRUE,
        'false' => TokenType::FALSE,
    ];
    // этот словарь используется, для игнорирования TokenType::OPERATOR_END в некоторых случаях
    private static $DEPTH_MAP = [
        TokenType::OPEN_RBRACE => 1,
        TokenType::OPEN_SBRACE => 1,

        TokenType::CLOSE_RBRACE => -1,
        TokenType::CLOSE_SBRACE => -1
    ];
    // словарь для экранирования символов в строке
    private static $ESCAPE_MAP = [
        'n' => "\n",
        'r' => "\r",
        't' => "\t",
        '0' => "\0"
    ];

    /**
     * @var int
     */
    private $pos;
    /**
     * @var int
     */
    private $len;
    /**
     * @var int
     */
    private $depth;
    /**
     * @var int
     */
    private $braceDepth;

    /**
     * @var array
     */
    private $result = null;
    /**
     * @var string
     */
    private $input;

    /**
     * Текущее позиция в строке(начиная с 1)
     * @var int
     */
    private $linePosition;
    /**
     * Начальная позиция токена
     * @var int
     */
    private $startLinePosition;
    /**
     * Текущий индекс строки(начиная с 1)
     * @var int
     */
    private $line;


    public function __construct(string $input){
        // заменяем "каретки" на обычный перенос строки для удобства
        $this->input = str::replace(str::replace($input, "\r\n", "\n"), "\r", "\n");
        $this->len = str::length($this->input);
    }

    /**
     * @throws LexerException
     */
    private function throwUnexpectedSymbol(): void{
        throw new LexerException(str::format("Unexpected symbol %s on line %d at %d", $this->current(), $this->line, $this->linePosition));
    }

    /**
     * @throws LexerException
     */
    private function throwUnexpectedEnd(): void{
        throw new LexerException(str::format("Unexpected end on line %d at %d", $this->line, $this->linePosition));
    }

    /**
     * @return array
     * @throws LexerException
     */
    public function lex(): array{
        // кэширование результата
        if(isset($this->result)){
            return $this->result;
        }
        $this->pos = 0;
        $this->linePosition = 1;
        $this->startLinePosition = 1;
        $this->line = 1;
        $this->result = [];

        // пока есть символ
        while($this->current() !== null){
            // одиночный комментарий
            if($this->is('/') && $this->is('/', 1)){
                $this->tokenizeOneLineComment();
            }
            // многосточный комментарий
            else if($this->is('/') && $this->is('*', 1)){
                $this->tokenizeMultiLineComment();
            }
            // имя
            else if($this->is('_') || $this->isLetter()){
                $this->tokenizeName();
            }
            else if($this->isNumber() || ($this->is('.') && $this->isNumber(1))){
                $this->tokenizeNumber();
            }
            // пропускаем пробел, табуляцию и т.д.
            else if($this->isWhiteSpace()){
                $this->tokenizeWhiteSpace();
            }
            // оператор
            else if(self::$OPERATORS[$this->current()]){
                $this->tokenizeOperator();
            }
            // строка
            else if($this->is('\'') || $this->is('"')){
                $this->tokenizeString();
            }
            // если встречен неожиданный символ
            else{
                $this->throwUnexpectedSymbol();
            }
        }

        $this->add(TokenType::OPERATOR_END);

        return $this->result;
    }

    /**
     * @return int
     */
    public function getDepth(): int{
        return $this->depth;
    }

    /**
     * @return int
     */
    public function getBraceDepth(): int{
        return $this->braceDepth;
    }

    /**
     * @throws LexerException
     */
    private function tokenizeOperator(): void{
        $operator = $this->current();

        // данная конструкция, позволяет находить длинные операторы(... например)
        while($this->current() !== null){
            $nextOperator = $operator.$this->peek(1);
            if(!isset(self::$OPERATORS[$nextOperator])){
                $this->pop();
                break;
            }
            $operator = $nextOperator;
            $this->pop();
        }
        if(self::$OPERATORS[$operator] == TokenType::UNDEFINED){
            $this->throwUnexpectedSymbol();
        }
        $this->add(self::$OPERATORS[$operator]);
    }

    /**
     * @throws LexerException
     */
    private function tokenizeString(): void{
        // запоминаем символ с которого стартовала наша строка(' или ")
        $start = $this->current();
        $this->pop();

        $string = '';
        $isEnded = false;
        $isEscape = false;

        while($this->current() !== null){
            if($this->is('\\')){
                if($isEscape){
                    // экранируем символы
                    if(isset(self::$ESCAPE_MAP[$this->current()])){
                        $string = self::$ESCAPE_MAP[$this->current()];
                    }
                    else{
                        $string .= $this->current();
                    }
                }
                $isEscape = !$isEscape;
            }
            // если это кавычка
            else if($this->is($start)){
                // экранируем
                if($isEscape){
                    $string .= $this->current();
                    $isEscape = false;
                }
                // завершаем строку
                // это единственное место, которая результирует об успешном завершении строки
                else{
                    $this->pop();
                    $isEnded = true;
                    break;
                }
            }
            else{
                $string .= $this->current();
            }
            $this->pop();
        }
        // если строка не завершена(например входные символы закончились)
        if(!$isEnded){
            throw new LexerException("String not completed on line {$this->line} at {$this->linePosition}");
        }
        $this->add(TokenType::STRING, $string);
    }

    private function tokenizeName(): void{
        $name = '';
        while($this->current() !== null && ($this->is('_') || $this->isNumber() || $this->isLetter())){
            $name .= $this->current();
            $this->pop();
        }

        if(isset(self::$KEYWORDS[$name])){
            $this->add(self::$KEYWORDS[$name]);
        }
        else{
            $this->add(TokenType::NAME, $name);
        }
    }

    /**
     * @throws LexerException
     */
    private function tokenizeNumber(): void{
        $number = '';
        $hasPoint = false;
        while($this->current() !== null && ($this->is('.') || $this->isNumber())){
            if($this->is('.')){
                // если встречено 2 точки
                if($hasPoint){
                    $this->throwUnexpectedSymbol();
                }
                $hasPoint = true;
            }
            $number .= $this->current();
            $this->pop();
        }

        // 5. тоже самое, что 5.0
        if(str::endsWith($number, '.')){
            $number = "{$number}0";
        }
        // .5 тоже самое, что 0.5
        else if(str::startsWith($number, '.')){
            $number = "0{$number}";
        }

        $this->add($hasPoint ? TokenType::FLOAT : TokenType::INT, $number);
    }

    private function tokenizeOneLineComment(): void{
        while($this->current() !== null && !$this->is("\n")){
            $this->pop();
        }
        $this->syncPosition();
    }

    private function tokenizeMultiLineComment(): void{
        $this->jump(2);
        while($this->current() !== null){
            if($this->is('*') && $this->is('/', 1)){
                $this->jump(2);
                break;
            }
            $this->pop();
        }
        $this->syncPosition();
    }

    private function tokenizeWhiteSpace(): void{
        while($this->current() !== null && $this->isWhiteSpace()){
            $this->pop();
        }
        $this->syncPosition();
    }

    private function count(): void{
        if($this->is("\n")){
            $this->line++;
            $this->linePosition = 1;
            $this->startLinePosition = 1;
        }
        else{
            $this->linePosition++;
        }
    }

    private function syncPosition(): void{
        $this->startLinePosition = $this->linePosition;
    }

    private function add(int $type, ?string $value = null): void{
        /** @var Token $last */
        $last = arr::last($this->result);
        // for shell
        if($type == TokenType::OPEN_BRACE || $type == TokenType::CLOSE_BRACE){
            $this->braceDepth += $type == TokenType::OPEN_BRACE ? 1 : -1;
        }
        if($last){
            // не позволяем добавить 2 OPERATOR_END подряд
            if($last->getType() == TokenType::OPERATOR_END && $type == TokenType::OPERATOR_END){
                return;
            }
            // не добавляем OPERATOR_END, если последний токен это фигурная скобка
            else if($type == TokenType::OPERATOR_END &&
                ($last->getType() == TokenType::OPEN_BRACE || $last->getType() == TokenType::CLOSE_BRACE)){
                return;
            }
        }
        // не добавляем OPERATOR_END, если список токенов пуст
        else if(!$last && $type == TokenType::OPERATOR_END){
            return;
        }
        // добавялем значение из словаря глубины, чтобы игнорировать OPERATOR_END если открыта скобка
        if(isset(self::$DEPTH_MAP[$type])){
            $this->depth += self::$DEPTH_MAP[$type];
        }

        // игнорируем OPERATOR_END, если значение глубины не равно 0
        if($type == TokenType::OPERATOR_END && $this->depth != 0){
            return;
        }

        $token = new Token($type, $value);
        $token->setLine($this->line);
        $token->setLinePosition($this->startLinePosition);
        $this->syncPosition();

        $this->result[] = $token;
    }

    private function isWhiteSpace(): bool{
        return $this->is(' ') || $this->is("\t");
    }
    private function isNumber(int $amount = 0): bool{
        return $this->inRange('0', '9', $amount);
    }
    private function isLetter(int $amount = 0): bool{
        return $this->inRange('a', 'z', $amount) || $this->inRange('A', 'Z', $amount);
    }

    /**
     * Функция проверяет, входит ли код символа в диапазон кодох двух других символов
     * @param string $a
     * @param string $b
     * @param int $amount
     * @return bool
     */
    private function inRange(string $a, string $b, int $amount = 0): bool{
        $c = char::ord($this->peek($amount));
        $a = char::ord($a);
        $b = char::ord($b);

        return $c >= $a && $c <= $b;
    }

    /**
     * Сравнивает выбранный символ(текущий при $amount = 0) с заданным символов
     * @param string $needed
     * @param int $amount
     * @return bool
     */
    private function is(string $needed, int $amount = 0): bool{
        $char = $this->peek($amount);
        return isset($char) && $char == $needed;
    }


    private function peek(int $amount): ?string{
        $pos = $this->pos + $amount;
        return $pos >= 0 && $pos < $this->len ? $this->input[$pos] : null;
    }
    private function current(): ?string{
        return $this->peek(0);
    }
    private function jump(int $amount): ?string{
        for($i = 0; $i < $amount; $i++){
            $this->count();
        }
        $this->pos += $amount;
        return $this->current();
    }
    private function pop(): ?string{
        return $this->jump(1);
    }



    /**
     * Создаёт лексер из какого-либо источинка
     * @param mixed $input
     * @return Lexer
     * @throws IOException
     */
    public static function ofInput($input): Lexer{
        if($input instanceof Stream){
            $stream = $input;
        }
        else{
            $stream = Stream::of($input);
        }

        try{
            return new Lexer($stream->readFully());
        }
        finally{
            $stream->close();
        }
    }
}