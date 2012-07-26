<?php
// Create image instances
$src  = imagecreatefrompng('114.png');
$dest = imagecreatetruecolor(72, 72);



// Copy
imagecopyresampled($dest, $src, 0, 0, 0, 0, 72, 72, 114, 114);

include('../../holidays.php');
$h = getNextRedDay();
$day = date('d',$h);

$img =  imagecreatefrompng('m'.date('m',$h).'.png');
imagealphablending($img, true);
imagecopyresampled($dest, $img, 0, 0, 0, 0, 72, 72, 114, 114);

$img =  imagecreatefrompng('d'.date('d',$h).'.png');
imagealphablending($img, true);
imagecopyresampled($dest, $img, 0, 0, 0, 0, 72, 72, 114, 114);


// Output and free from memory
header('Content-Type: image/png');
imagepng($dest);

imagedestroy($dest);
imagedestroy($img);
imagedestroy($src);
?>