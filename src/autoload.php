<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "php-sql-parser.php";
spl_autoload_register(function($className){
    if (strpos($className, "Pseudo") === 0) {
       $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);
       require_once __DIR__ . DIRECTORY_SEPARATOR . $classPath . ".php";
    }
});