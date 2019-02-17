<?php


namespace trash\common\value;


use trash\common\Environment;

interface Value{
    function plus(Environment $environment, Value $other): Value;
    function minus(Environment $environment, Value $other): Value;
    function mul(Environment $environment, Value $other): Value;
    function div(Environment $environment, Value $other): Value;
    function rem(Environment $environment, Value $other): Value;

    function equal(Environment $environment, Value $other): Value;
    function notEqual(Environment $environment, Value $other): Value;
    function less(Environment $environment, Value $other): Value;
    function more(Environment $environment, Value $other): Value;
    function equalLess(Environment $environment, Value $other): Value;
    function equalMore(Environment $environment, Value $other): Value;

    function not(): Value;

    function arrayGet(Environment $environment, string $key): Value;
    function arraySet(Environment $environment, string $key, Value $value): Value;

    function invoke(Environment $environment, Value ...$values): Value;

    function getName(): string;
}