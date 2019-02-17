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
    const MUL = 208;            // *
    const DIV = 209;            // /
    const REM = 210;            // %

    // assigment operators
    const EQ = 211;             // =
    const EQ_PLUS = 212;        // +=
    const EQ_MINUS = 213;       // -=
    const EQ_MUL = 214;         // *=
    const EQ_DIV = 215;         // /=
    const EQ_REM = 216;         // %=

    // logic operators
    const EQ_EQ = 217;          // ==
    const NOT_EQ = 218;         // !=
    const LESS = 219;           // <
    const MORE = 220;           // >
    const EQ_LESS = 221;        // <=
    const EQ_MORE = 222;        // >=
    const NOT = 223;            // ! or not

    const COMMA = 224;          // ,
    const POINT = 225;          // .
    const COLON = 226;          // :
    const SEMICOLON = 227;      // ;
    const ELLIPSIS = 228;       // ...


    // blocks
    const IF = 300;             // if (condition){}
    const ELIF = 301;           // elif(condition){}
    const ELSE = 302;           // else{}
    const FOR = 303;            // for(a, b, ... : iterable){}
    const WITH = 304;           // while(condition){}
    const FUNCTION = 305;       // func(a, b=123, ...){}

    // keywords
    const RETURN = 307;         // return expression
    const PRINT = 308;          // print expression


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
}