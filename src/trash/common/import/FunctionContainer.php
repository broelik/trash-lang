<?php


namespace trash\common\import;


use trash\common\Environment;

class FunctionContainer{
    public function register(Environment $environment): void{
        $clazz = new \ReflectionClass($this);
    }
}