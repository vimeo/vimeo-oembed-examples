<?php

// Change this to your username to load in your videos
$vimeo_user_name = ($_GET['user']) ? $_GET['user'] : 'brad';

// API endpoint
$api_endpoint = 'http://www.vimeo.com/api/v2/'.$vimeo_user_name;

// Curl helper function
function curl_get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}

// Load the user info and clips
$user = simplexml_load_string(curl_get($api_endpoint.'/info.xml'));
$videos = simplexml_load_string(curl_get($api_endpoint.'/videos.xml'));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Vimeo Simple API PHP Example</title>
	<style type="text/css">
		ul { list-style-type: none; margin: 0; padding: 0; }
		li { display: inline; padding: 0; margin: 10px 2px; }
		img { border: 0; }
		img#portrait { float: left; margin-right: 5px; }
		#stats { clear: both; }
	</style>
</head>
<body>
	
	<h1>Vimeo Simple API PHP Example</h1>
	<div id="stats">
		<img id="portrait" src="<?=$user->user->portrait_small?>" />
		<h2><?=$user->user->display_name?>'s Videos</h2>
	</div>
	<p id="bio"><?=($user->user->bio)?></p>
	<div id="thumbs">
		<ul>
		<?php foreach ($videos->video as $video): ?>
			<li>
				<a href="<?=$video->url?>"><img src="<?=$video->thumbnail_medium?>" /></a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	
</body>
</html>
