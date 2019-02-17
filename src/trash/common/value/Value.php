<?php


namespace trash\common\value;


use trash\common\Environment;

interface Value{
    function plus(Environment $environment, Value $other): Value;
    function minus(Environment $environment, Value $other): Value;
    function mul(Environment $environment, Value $other): Value;
    function div(Environment $environment, Value $other): Value;
    function rem(Environment $environment, Value $other): Value;

    function equal(Environment $environment, Value $other): BooleanValue;
    function notEqual(Environment $environment, Value $other): BooleanValue;
    function less(Environment $environment, Value $other): BooleanValue;
    function more(Environment $environment, Value $other): BooleanValue;
    function equalLess(Environment $environment, Value $other): BooleanValue;
    function equalMore(Environment $environment, Value $other): BooleanValue;

    function invert(): Value;
    function not(): BooleanValue;

    function arrayGet(Environment $environment, string $key): Value;
    function arraySet(Environment $environment, string $key, Value $value): Value;

    function invoke(Environment $environment, Value ...$values): Value;

    function getName(): string;

    function toString(): string;
    function toInteger(): int;
    function toFloat(): float;
    function toBoolean(): bool;
    function toArray(): array;

    function stringValue(): StringValue;
    function integerValue(): IntegerValue;
    function floatValue(): FloatValue;
    function booleanValue(): BooleanValue;
    function arrayValue(): ArrayValue;
}