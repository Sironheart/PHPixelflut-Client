#!/usr/bin/env php
<?php

require "vendor/autoload.php";

if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3]) || $argv[1] == '--help' || $argv[1] == 'help' || $argv[1] == '-h') {
    echo "This File needs 3 parameters" . PHP_EOL;
    echo "Parameter 1 is the IP-Address of the 'Pixelflut' Servers" . PHP_EOL;
    echo "Parameter 2 is a valid port number";
    echo "Parameter 3 is the path to an image";
    die("'help', '--help' and '-h' show you this help" . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL);
}
elseif (preg_match('/[0-2]{0,1}[0-9]{1,2}\.[0-2]{0,1}[0-9]{1,2}\.[0-2]{0,1}[0-9]{1,2}\.[0-2]{0,1}[0-9]{1,2}/', $argv[1]) === 0) {
    die('Der erste Parameter muss einer IPv4 Adresse entsprechen!');
}
elseif ($argv[2] > 65535 || $argv < 0) {
    die('Der zweite Port muss eine gültige Portnummer sein');
}

$img = null;
$file = explode('.', $argv[3]);

switch (end($file)) {
    case 'bmp':
        $img = imagecreatefrombmp($argv[3]);
        break;
    case 'gd2':
        $img = imagecreatefromgd2($argv[3]);
        break;
    case 'gd':
        $img = imagecreatefromgd($argv[3]);
        break;
    case 'gif':
        $img = imagecreatefromgif($argv[3]);
        break;
    case 'jpg':
    case 'jpeg':
    $img = imagecreatefromjpeg($argv[3]);
        break;
    case 'png':
        $img = imagecreatefrompng($argv[3]);
        break;
    case 'wbmp':
        $img = imagecreatefromwbmp($argv[3]);
        break;
    case 'webp':
        $img = imagecreatefromwbmp($argv[3]);
        break;
    case 'xbm':
        $img = imagecreatefromxbm($argv[3]);
        break;
    case 'xpm':
        $img = imagecreatefromxpm($argv[3]);
        break;
    default:
        die('The current path is not an image!');
}

$rgbImg = array();

$randX = rand(0, 300);
$randY = rand(0, 300);

for ($x = 0; $x < imagesx($img); $x++) {
    for($y = 0; $y < imagesy($img); $y++) {
        $index = imagecolorat($img, $x, $y);
        $rgbColors = imagecolorsforindex($img, $index);
        $color = sprintf('%02x%02x%02x',
            $rgbColors['red'],
            $rgbColors['green'],
            $rgbColors['blue']);
        $rgbImg[($x + $randX) . ' ' . ($y + $randY)] = $color;
    }
}

echo 'Connecting with ' . $argv[1] . ':' . $argv[2] . PHP_EOL . PHP_EOL;

$fileStream = fsockopen($argv[1], $argv[2]);

while(true) {
    foreach ($rgbImg as $key => $value)
    {
        $stringBuild = 'PX ' . $key . ' ' . $value . PHP_EOL;
        fwrite($fileStream, $stringBuild);
    }
}
