<?

// Change this to your username to load in your clips
$vimeo_user_name = ($_GET['user']) ? $_GET['user'] : 'brad';

// Endpoints
$api_endpoint = 'http://vimeo.com/api/v2/'.$vimeo_user_name;
$oembed_endpoint = 'http://vimeo.com/api/oembed.xml';

// Curl helper function
function curl_get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}

// Get the url for the latest video
$videos = simplexml_load_string(curl_get($api_endpoint . '/videos.xml'));
$video_url = $videos->video[0]->url;

// Create the URL
$oembed_url = $oembed_endpoint . '?url=' . rawurlencode($video_url);

// Load in the oEmbed XML
$oembed = simplexml_load_string(curl_get($oembed_url));
$embed_code = html_entity_decode($oembed->html);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Vimeo Simple API and oEmbed Example</title>
</head>
<body>

	<h1>Vimeo Simple API and oEmbed Example</h1>
	<?php echo $embed_code ?>

</body>
</html>