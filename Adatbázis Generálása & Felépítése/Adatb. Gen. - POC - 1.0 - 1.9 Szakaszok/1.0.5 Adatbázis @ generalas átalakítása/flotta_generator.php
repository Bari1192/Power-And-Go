<?php

require_once __DIR__ . '/vendor/autoload.php';

use Acme\FlottaTipusok\FlottaTipusok;

$outputDir = __DIR__ . '/output';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$flotta = new FlottaTipusok();
$sqlContent = $flotta->generateSQL();

$outputFile = $outputDir . '/flotta_tipusok.sql';
file_put_contents($outputFile, $sqlContent);

echo "SQL fájl generálása sikeres: " . $outputFile . PHP_EOL;
