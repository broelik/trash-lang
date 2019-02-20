<?php


namespace trash\lexer;


class TokenType{
    private static $BY_VALUE = null;

    const UNDEFINED = 0;        // system

    // types
    const NAME = 100;           // _name, name1, nA_me0
    const STRING = 101;         // 'string' or "string"
    const TRUE = 102;           // true
    const FALSE = 103;          // false
    const NULL = 104;           // null
    const INT = 105;            // 45, 23, 54
    const FLOAT = 106;          // 50.5, 0.5, 43.7


    const OPERATOR_END = 200;   // ; or \n sometimes

    // braces
    const OPEN_BRACE = 201;     // {
    const CLOSE_BRACE = 202;    // }
    const OPEN_RBRACE = 203;    // (
    const CLOSE_RBRACE = 204;   // )
    const OPEN_SBRACE = 205;    // [
    const CLOSE_SBRACE = 206;   // ]

    // math operators
    const PLUS = 207;           // +
    const MINUS = 208;          // -
    const MUL = 209;            // *
    const DIV = 210;            // /
    const REM = 211;            // %

    // assigment operators
    const EQ = 212;             // =
    const EQ_PLUS = 213;        // +=
    const EQ_MINUS = 214;       // -=
    const EQ_MUL = 215;         // *=
    const EQ_DIV = 216;         // /=
    const EQ_REM = 217;         // %=

    // logic operators
    const EQ_EQ = 218;          // ==
    const NOT_EQ = 219;         // !=
    const LESS = 220;           // <
    const MORE = 221;           // >
    const EQ_LESS = 222;        // <=
    const EQ_MORE = 223;        // >=
    const NOT = 224;            // ! or not

    const COMMA = 225;          // ,
    const POINT = 226;          // .
    const COLON = 227;          // :
    const ELLIPSIS = 228;       // ...


    // blocks
    const IF = 300;             // if (condition){}
    const ELIF = 301;           // elif(condition){}
    const ELSE = 302;           // else{}
    const FOR = 303;            // for(a, b, ... : iterable){}
    const WHILE = 304;            // for(a, b, ... : iterable){}
    const WITH = 305;           // while(condition){}
    const FUNCTION = 306;       // func(a, b=123, ...){}

    // keywords
    const RETURN = 307;         // return expression
    const PRINT = 308;          // print expression
    const CONTINUE = 310;       // continue
    const BREAK = 311;          // break


    /**
     * @param int $value
     * @return string
     */
    public static function getName(int $value): ?string{
        try{
            if(!isset(self::$BY_VALUE)){
                self::$BY_VALUE = [];
                $clazz = new \ReflectionClass(TokenType::class);
                foreach($clazz->getConstants() as $name => $subValue){
                    self::$BY_VALUE[$subValue] = $name;
                }
            }

            return self::$BY_VALUE[$value];
        }
        catch(\ReflectionException $e){
            return null;
        }
    }

    public static function operatorToString(int $type): ?string{
        switch($type){
            case TokenType::EQ:
                return '=';
            case TokenType::EQ_PLUS:
                return '+=';
            case TokenType::EQ_MINUS:
                return '-=';
            case TokenType::EQ_MUL:
                return '*=';
            case TokenType::EQ_DIV:
                return '/=';
            case TokenType::EQ_REM:
                return '%=';

            case TokenType::PLUS:
                return '+';
            case TokenType::MINUS:
                return '-';
            case TokenType::MUL:
                return '*';
            case TokenType::DIV:
                return '/';
            case TokenType::REM:
                return '%';
            case TokenType::NOT:
                return '!';

            case TokenType::EQ_EQ:
                return '==';
            case TokenType::EQ_LESS:
                return '<=';
            case TokenType::EQ_MORE:
                return '>=';
            case TokenType::LESS:
                return '<';
            case TokenType::MORE:
                return '>';
            case TokenType::NOT_EQ:
                return '!=';
        }

        return null;
    }
}