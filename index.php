<?php
putenv('GDFONTPATH=' . realpath('.'));

header('Content-Type: image/png');

$image = imagecreatetruecolor(500, 500);
$white = imagecolorallocate($image, 255, 255, 255);

$src = imagecreatefrompng('forzly_bg.png');
imagecopymerge($image, $src, 0, 0, 0, 0, 500, 500, 100);

$font = 'Ubuntu';
$fontSize = 20;
$inputText = (string) isset($_GET['t']) ? $_GET['t'] : 'Lorem ipsum';
$textArray = explode(' ', $inputText);

$bbox = imagettfbbox($fontSize, 0, $font, $inputText);

$startPosition = 0;
$offset = 1;
$lines = [];

for ($i = 0; $i < count($textArray); $i++)
{
    $line = implode(' ', array_slice($textArray, $startPosition, $offset));

    $bbox = imagettfbbox($fontSize, 0, $font, $line);
    $lineWidth = ($bbox[0] + $bbox[4]);

    if ($lineWidth >= 320 || $i == count($textArray) - 1)
    {
        $lines[] = $line;
        $startPosition = $i + count($lines);
        $offset = 1;
    }

    $offset++;
}

$heightModifier = 0;
$lineHeight = 10;

for ($i = 0; $i < count($lines); $i++)
{
    $bbox = imagettfbbox($fontSize, 0, $font, $lines[$i]);

    $lineWidth = ($bbox[0] + $bbox[4]);

    $textHeight = count($lines) * $lineHeight;

    $x = (imagesx($image) / 2) - $lineWidth / 2;
    $y = (imagesy($image) / 2) - ($textHeight / 2) + 30 * $i;

    imagettftext($image, $fontSize, 0, $x, $y, $white, $font, $lines[$i]);
}

imagepng($image);
imagedestroy($image);