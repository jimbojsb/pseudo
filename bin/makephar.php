<?php

$phar = new Phar(__DIR__ . '/../pseudo.phar');
$phar->buildFromDirectory(__DIR__ . '/../src');

$runFile = file_get_contents(__DIR__ . '/../src/autoload.php');
$runFile = str_replace("__DIR__", "'phar://' . __FILE__ ", $runFile);
$runFile = str_replace('/../src', '', $runFile) . PHP_EOL;
$runFile .= "Phar::mapPhar();" . PHP_EOL;
$runFile .= "__HALT_COMPILER();" . PHP_EOL;
$phar->setStub($runFile);

//quick test to make sure it works
require_once __DIR__ . '/../pseudo.phar';
$p = new Pseudo\Pdo();
