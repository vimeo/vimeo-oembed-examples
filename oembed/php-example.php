<?

/*
You may want to use oEmbed discovery instead of hard-coding the oEmbed endpoint.
*/
$oembed_endpoint = 'http://vimeo.com/api/oembed';

// Grab the video url from the url, or use default
$video_url = ($_GET['url']) ? $_GET['url'] : 'http://vimeo.com/7100569';

// Create the URLs
$json_url = $oembed_endpoint . '.json?url=' . rawurlencode($video_url) . '&width=640';
$xml_url = $oembed_endpoint . '.xml?url=' . rawurlencode($video_url) . '&width=640';

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

// Load in the oEmbed XML
$oembed = simplexml_load_string(curl_get($xml_url));

/*
    An alternate approach would be to load JSON,
    then use json_decode() to turn it into an array.
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vimeo PHP oEmbed Example</title>
</head>
<body>

    <h1><?php echo $oembed->title ?></h1>
    <h2>by <a href="<?php echo $oembed->author_url ?>"><?php echo $oembed->author_name ?></a></h2>

    <?php echo html_entity_decode($oembed->html) ?>

</body>
</html>