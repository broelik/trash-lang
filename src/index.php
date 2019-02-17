<?php

$lexer = \trash\lexer\Lexer::ofInput('data/simple.tsh');
var_dump($lexer->lex());