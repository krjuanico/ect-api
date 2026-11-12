<?php

$routeFiles = glob(__DIR__.'/api/*.routes.php');
sort($routeFiles);

foreach ($routeFiles as $routeFile) {
    require $routeFile;
}