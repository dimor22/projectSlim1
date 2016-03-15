<?php
/**
 *  Helper functions
 */


// Dump and Die
function dd($var) {
	var_dump($var);
	die;
}

function getSliderPhotos(){
	return ORM::for_table('photos')->where('slider', '1')->find_many();
}

function getGalleryPhotos ( $album ){

	$album = ORM::for_table('photos')->where('album', $album)->find_many();

	$gallery = [];
	$innerCounter = 0;

	// creates gallery with 4 images per row
	for($i = 0;$i < count($album); $i++){
		if ($i % 4 == 0) { $innerCounter++; }
		$gallery[$innerCounter][] = $album[$i];
	}

	return $gallery;
}

function checkHost(){
	return $_SERVER['SERVER_NAME'];
}