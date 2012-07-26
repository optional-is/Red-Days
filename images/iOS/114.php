<?php
// Create image instances
$src  = imagecreatefrompng('114.png');
$dest = imagecreatetruecolor(114, 114);



// Copy
imagecopy($dest, $src, 0, 0, 0, 0, 114, 114);

include('../../holidays.php');
$h = getNextRedDay();
$day = date('d',$h);

$img =  imagecreatefrompng('m'.date('m',$h).'.png');
imagealphablending($img, true);
imagecopy($dest, $img, 0, 0, 0, 0, 114, 114);

$img =  imagecreatefrompng('d'.date('d',$h).'.png');
imagealphablending($img, true);
imagecopy($dest, $img, 0, 0, 0, 0, 114, 114);


// Output and free from memory
header('Content-Type: image/png');
imagepng($dest);

imagedestroy($dest);
imagedestroy($img);
imagedestroy($src);
?>