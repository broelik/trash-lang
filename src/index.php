<?php

//$lexer = \trash\lexer\Lexer::ofInput('data/simple.tsh');
//var_dump($lexer->lex());

$env = new \trash\common\Environment();
$int = new \trash\common\value\IntegerValue(5);
$float = new \trash\common\value\FloatValue(2.2);
$bool = \trash\common\value\BaseValue::FALSE();
$string = new \trash\common\value\StringValue("kek");
$arr1 = new \trash\common\value\ArrayValue($int, $float);
$arr2 = new \trash\common\value\ArrayValue($string, $bool);
$null = \trash\common\value\BaseValue::NULL();

var_dump($int->plus($env, $float));
var_dump($arr1->plus($env, $arr2));