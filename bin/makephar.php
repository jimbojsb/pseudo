<?php
$phar = new Phar(__DIR__ . '/../pseudo.phar');
$phar->buildFromDirectory(__DIR__ . '/../src');

$runFile = implode(PHP_EOL, array_slice(file(__DIR__ . '/../src/autoload.php'), 2));
$runFile = str_replace("__DIR__", "'phar://' . __FILE__ ", $runFile);
$runFile = str_replace('/../src', '', $runFile);

$stub .= "<?php" . PHP_EOL;
$stub .= "Phar::mapPhar();" . PHP_EOL;
$stub .= $runFile . PHP_EOL;
$stub .= "__HALT_COMPILER();" . PHP_EOL;
$phar->setStub($stub);
