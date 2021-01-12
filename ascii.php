<?php

function chars($brightness) { // prints the char
	$brightness = $brightness * 2.25;
	if ($brightness <= 25)
		echo("@");
	else if ($brightness > 25 && $brightness <= 50)
		echo("0");
	else if ($brightness > 50 and $brightness <= 75)
		echo("O");
	else if ($brightness > 75 and $brightness <= 90)
		echo("B");
	else if ($brightness > 90 and $brightness <= 100)
		echo("d");
	else if ($brightness > 100 and $brightness <= 125)
		echo("a");
	else if ($brightness > 125 and $brightness <= 135)
		echo("q");
	else if ($brightness > 135 and $brightness <= 150)
		echo("o");
	else if ($brightness > 150 and $brightness <= 175)
		echo("c");
	else if ($brightness > 175 and $brightness <= 200)
		echo(".");
	else if ($brightness > 200 and $brightness <= 225)
		echo("*");
	else
		echo(" ");

}

function work($fp, $width, $height, $ext) { // gets brightness per pixel
	$row = 1;
	$col = 1;
	if ($ext == "png")
		$im = imagecreatefrompng($fp);
	else if ($ext == "jpeg")
		$im = imagecreatefromjpeg($fp);
	echo("<pre>");
	foreach (range(1,$height) as $num) {
		while ($row < $width - 1) { // loop through each pixel and get brightness
			$row += 1;
			$rgb = imagecolorat($im, $row, $col);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			$brightness = ((($r * 299) + ($g * 587) + ($b * 114)) / 1000);
			chars($brightness);
		}
		$row = 0;
		$col += 1;
		echo("<br>\n");
	}
	echo("</pre>");
	unlink($fileTmpPath);
	exit();
}

function error($err, $fileTmpPath) {
	unlink($fileTmpPath);
	die($err); // delete tmp image then exit
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Only run if accessed through POST for upload
	//First we check the file is valid
	$maxuploadsize = 4194306; //Max file size in bytes


	if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK)
	    	die("An error occured uploading the file");

	// get details of the uploaded file
	$fileTmpPath = $_FILES['file']['tmp_name'];
	$fileName = $_FILES['file']['name'];
	$fileSize = $_FILES['file']['size'];

	$ext = pathinfo($fileName, PATHINFO_EXTENSION);

	if ($fileSize > $maxuploadsize)
		error("File is too large, max is ".(string)(round($maxuploadsize / 1048576, 2))." MB",$fileTmpPath);

	if(!exif_imagetype($fileTmpPath))
    		error("File is not a valid image", $fileTmpPath);

	$width = getimagesize($fileTmpPath)[0];
	$height = getimagesize($fileTmpPath)[1];

	work($fileTmpPath, $width, $height, $ext);
}
?>
<!-- Credit: github.com/CoffeePanda0 !-->
<title> Image2ASCII </title>
<h1> epic image to ascii converter </h1>
<p> just upload the image, VIEW SOURCE, then zoom out to witness the masterpiece </p>

<form action = "" method="post" enctype="multipart/form-data">
	<p><u> Max file size = 4MB </u></p>
	<input type="file" name="file" id="file" accept="image/png, image/jpeg">
	<input type="submit" value="Upload Image" name="submit">
</form>
