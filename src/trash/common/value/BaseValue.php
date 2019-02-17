<?php


namespace trash\common\value;


use trash\common\Environment;

abstract class BaseValue implements Value{
    function arrayGet(Environment $environment, string $key): Value{
        throw new \RuntimeException("Unable to use {$this->getName()} as array");
    }

    function arraySet(Environment $environment, string $key, Value $value): Value{
        throw new \RuntimeException("Unable to use {$this->getName()} as array");
    }

    function invoke(Environment $environment, Value ...$values): Value{
        throw new \RuntimeException("Unable to invoke {$this->getName()}");
    }
}