<?php

// The Simple API URL
$api_endpoint = 'http://vimeo.com/api/v2/';

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

if ($_GET['album']) {

	// Get the album
	$album_id = $_GET['album'];

	// Load the videos and info
	$videos = simplexml_load_string(curl_get($api_endpoint . 'album/' . $album_id . '/videos.xml'));
	$info = simplexml_load_string(curl_get($api_endpoint . 'album/' . $album_id . '/info.xml'));

	// Thumbnail and title
	$image = $info->album->thumbnail;
	$title = $info->album->title;

}
else if ($_GET['group']) {

	// Get the group
	$group_id = $_GET['group'];

	// Load the videos and info
	$videos = simplexml_load_string(curl_get($api_endpoint . 'group/' . $group_id . '/videos.xml'));
	$info = simplexml_load_string(curl_get($api_endpoint . 'group/' . $group_id . '/info.xml'));

	// Thumbnail and title
	$image = $info->group->thumbnail;
	$title = $info->group->name;

}
else if ($_GET['channel']) {

	// Get the channel
	$channel_id = $_GET['channel'];

	// Load the videos and info
	$videos = simplexml_load_string(curl_get($api_endpoint . 'channel/' . $channel_id . '/videos.xml'));
	$info = simplexml_load_string(curl_get($api_endpoint . 'channel/' . $channel_id . '/info.xml'));

	// Thumbnail and title
	$image = null;
	$title = $info->channel->name;

}
else {

	// Change this to your username to load in your videos
	$vimeo_user_name = ($_GET['user']) ? $_GET['user'] : 'brad';

	// Load the user's videos
	$videos = simplexml_load_string(curl_get($api_endpoint.$vimeo_user_name . '/videos.xml'));

	// Thumbnail and title
	$image = $videos->video[0]->user_portrait_medium;
	$title = $videos->video[0]->user_name . "'s Videos";

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Vimeo Simple API Gallery Example</title>

	<style>
		#thumbs { overflow: auto; height: 298px; width: 300px; border: 1px solid #E7E7DE; padding: 0; float: left; }
		#thumbs ul { list-style-type: none; margin: 0 10px 0; padding: 0 0 10px 0; }
		#thumbs ul li { height: 75px; }

		.thumb { border: 0; float: left; width: 100px; height: 75px; background: url(http://a.vimeocdn.com/thumbnails/defaults/default.75x100.jpg); margin-right: 10px; }

		#embed { background-color: #E7E7DE; height: 280px; width: 504px; float: left; padding: 10px; }

		#portrait { float: left; margin-right: 5px; max-width: 100px; }
		#stats { clear: both; margin-bottom: 20px; }
	</style>

	<script>

		// Tell Vimeo what function to call
		var oEmbedCallback = 'embedVideo';

		// Set up the URL
		var oEmbedUrl = 'http://vimeo.com/api/oembed.json';

		// Load the first one in automatically?
		var loadFirst = true;

		// This function puts the video on the page
		function embedVideo(video) {
			var videoEmbedCode = video.html;
			document.getElementById('embed').innerHTML = unescape(videoEmbedCode);
		}

		// This function runs when the page loads and adds click events to the links
		function init() {
			var links = document.getElementById('thumbs').getElementsByTagName('a');

			for (var i = 0; i < links.length; i++) {
				// Load a video using oEmbed when you click on a thumb
				if (document.addEventListener) {
					links[i].addEventListener('click', function(e) {
						var link = this;
						loadScript(oEmbedUrl + '?url=' + link.href + '&width=504&height=280&callback=' + oEmbedCallback);
						e.preventDefault();
					}, false);
				}
				// IE (sucks)
				else {
					links[i].attachEvent('onclick', function(e) {
						var link = e.srcElement.parentNode;
						loadScript(oEmbedUrl + '?url=' + link.href + '&width=504&height=280&callback=' + oEmbedCallback);
						return false;
					});
				}
			}

			// Load in the first video
			if (loadFirst) {
				loadScript(oEmbedUrl + '?url=' + links[0].href + '&height=280&width=504&callback=' + oEmbedCallback);
			}
		}

		// This function loads the data from Vimeo
		function loadScript(url) {
			var js = document.createElement('script');
			js.setAttribute('src', url);
			document.getElementsByTagName('head').item(0).appendChild(js);
		}

		// Call our init function when the page loads
		window.onload = init;

	</script>
</head>
<body>

	<h1>Vimeo Simple API Gallery Example</h1>
	<div id="stats">
		<img id="portrait" src="<?php echo $image ?>" />
		<h2><?php echo $title ?></h2>
		<div style="clear: both;"></div>
	</div>
	<div id="wrapper">
		<div id="embed"></div>
		<div id="thumbs">
			<ul>
			<?php foreach ($videos->video as $video): ?>
				<li>
					<a href="<?php echo $video->url ?>">
						<img src="<?php echo $video->thumbnail_medium ?>" class="thumb" />
						<p><?=$video->title?></p>
					</a>
				</li>
			<?php endforeach ?>
			</ul>
		</div>
	</div>

</body>
</html>