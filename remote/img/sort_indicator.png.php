<?php
// set up array of points for polygon

if(isset($_GET['dir']) && $_GET['dir']=="up") {
	$values = array(
		20,  14,  // Point 1 (x, y)
		1,  14, // Point 2 (x, y)
		10,  1  // Point 3 (x, y)
	);
} else {
	$values = array(
		1,  1,  // Point 1 (x, y)
		20,  1, // Point 2 (x, y)
		10,  14  // Point 3 (x, y)
	);
}

// create image
$image = imagecreatetruecolor(20, 14);

// allocate colors
$bg   = imagecolorallocate($image, 255, 255, 255);
//$blue = imagecolorallocate($image, 0, 0, 255);
$black = imagecolorallocate($image, 0, 0, 0);
	
imagecolortransparent($image, $bg);

// fill the background
imagefilledrectangle($image, 0, 0, 249, 249, $bg);

// draw a polygon
imagefilledpolygon($image, $values, 3, $black);

// flush image
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>